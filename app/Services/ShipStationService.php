<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShipStationService
{
    protected string $apiKey;
    protected string $apiSecret;
    protected string $apiUrl;
    protected string $fromPostalCode;
    protected string $fromState;
    protected string $fromCountry;

    public function __construct()
    {
        $this->apiKey = config('shipstation.api_key', env('SHIPSTATION_API_KEY', ''));
        $this->apiSecret = config('shipstation.api_secret', env('SHIPSTATION_API_SECRET', ''));
        $this->apiUrl = config('shipstation.api_url', 'https://ssapi.shipstation.com');
        $this->fromPostalCode = config('shipstation.from_postal_code', '90210');
        $this->fromState = config('shipstation.from_state', 'CA');
        $this->fromCountry = config('shipstation.from_country', 'US');
    }

    /**
     * Get live shipping rates from ShipStation API
     *
     * @param array $payload
     * @return array
     */
    public function getRates(array $payload): array
    {
        $toPostalCode = $payload['toPostalCode'] ?? $payload['shipping_address']['pincode'] ?? '90001';
        $toState = $payload['toState'] ?? $payload['shipping_address']['state_code'] ?? 'CA';
        $toCity = $payload['toCity'] ?? $payload['shipping_address']['city'] ?? 'Los Angeles';
        $toCountry = $payload['toCountry'] ?? $payload['shipping_address']['country_code'] ?? 'US';
        $cartTotal = floatval($payload['cartTotal'] ?? 0);
        $products = $payload['products'] ?? [];

        // Calculate total weight in ounces
        $totalWeightOunces = $this->calculateWeight($products, $payload['weight'] ?? null);

        // Standard carriers to query or fallback
        $rates = [];

        if (!empty($this->apiKey) && !empty($this->apiSecret)) {
            try {
                $authHeader = 'Basic ' . base64_encode("{$this->apiKey}:{$this->apiSecret}");

                // Common carriers in ShipStation: stamps_com (USPS), fedex, ups
                $carriers = ['stamps_com', 'ups', 'fedex'];

                foreach ($carriers as $carrier) {
                    $response = Http::withHeaders([
                        'Authorization' => $authHeader,
                        'Content-Type' => 'application/json',
                    ])->timeout(4)->post("{$this->apiUrl}/shipments/getrates", [
                        'carrierCode' => $carrier,
                        'serviceCode' => null,
                        'packageCode' => 'package',
                        'fromPostalCode' => $this->fromPostalCode,
                        'toPostalCode' => $toPostalCode,
                        'toState' => $toState,
                        'toCity' => $toCity,
                        'toCountry' => $toCountry,
                        'weight' => [
                            'value' => max(1, $totalWeightOunces),
                            'units' => 'ounces',
                        ],
                        'dimensions' => [
                            'units' => 'inches',
                            'length' => 10,
                            'width' => 8,
                            'height' => 6,
                        ],
                        'confirmation' => 'none',
                        'residential' => true,
                    ]);

                    if ($response->successful()) {
                        $responseData = $response->json();
                        if (is_array($responseData)) {
                            foreach ($responseData as $rate) {
                                $rates[] = [
                                    'id' => 'ss_' . ($rate['serviceCode'] ?? uniqid()),
                                    'carrierCode' => $rate['carrierCode'] ?? $carrier,
                                    'carrierName' => strtoupper($rate['carrierCode'] ?? $carrier),
                                    'serviceCode' => $rate['serviceCode'] ?? 'standard',
                                    'serviceName' => $rate['serviceName'] ?? 'Standard Shipping',
                                    'totalCost' => floatval($rate['shipmentCost'] ?? $rate['otherCost'] ?? 0),
                                    'estimatedDays' => isset($rate['serviceName']) && str_contains(strtolower($rate['serviceName']), 'express') ? '1-2 Business Days' : '3-5 Business Days',
                                    'badge' => isset($rate['serviceName']) && str_contains(strtolower($rate['serviceName']), 'express') ? 'Fastest' : 'Best Value',
                                ];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('ShipStation API call exception: ' . $e->getMessage());
            }
        }

        // If no rates were returned from live API, return calculated dynamic rates
        if (empty($rates)) {
            $rates = $this->getDynamicFallbackRates($toState, $toPostalCode, $totalWeightOunces, $cartTotal);
        }

        return $rates;
    }

    /**
     * Calculate total weight of cart items in ounces
     */
    protected function calculateWeight(array $products, $explicitWeight = null): float
    {
        if ($explicitWeight !== null && floatval($explicitWeight) > 0) {
            return floatval($explicitWeight) * 16; // if weight was in lbs
        }

        $totalOunces = 0;
        foreach ($products as $item) {
            $qty = intval($item['quantity'] ?? 1);
            $productId = $item['product_id'] ?? null;
            $weight = 0;

            if ($productId) {
                $product = Product::find($productId);
                if ($product && !empty($product->weight)) {
                    $weight = floatval($product->weight);
                }
            }

            // Default 1.5 lbs (24 oz) per product if not specified
            if ($weight <= 0) {
                $weight = 24;
            }

            $totalOunces += ($weight * $qty);
        }

        return max(16, $totalOunces);
    }

    /**
     * Generate dynamic rate options according to distance, weight, and cart total
     */
    public function getDynamicFallbackRates(string $state, string $zip, float $weightOunces, float $cartTotal): array
    {
        $weightLbs = ceil($weightOunces / 16);
        $baseRate = 7.50 + ($weightLbs * 0.85);

        // Free shipping if cart total >= $99
        $isFreeEligible = $cartTotal >= 99.0;
        $standardCost = $isFreeEligible ? 0.0 : round($baseRate, 2);
        $priorityCost = round($baseRate + 6.50, 2);
        $expressCost = round($baseRate + 18.00, 2);

        return [
            [
                'id' => 'ss_usps_ground',
                'carrierCode' => 'usps',
                'carrierName' => 'USPS Ground Advantage',
                'serviceCode' => 'usps_ground_advantage',
                'serviceName' => 'USPS Ground Advantage',
                'totalCost' => $standardCost,
                'estimatedDays' => '3-5 Business Days',
                'badge' => $isFreeEligible ? 'Free Shipping' : 'Best Value',
            ],
            [
                'id' => 'ss_ups_ground',
                'carrierCode' => 'ups',
                'carrierName' => 'UPS Ground',
                'serviceCode' => 'ups_ground',
                'serviceName' => 'UPS Ground',
                'totalCost' => round($baseRate + 2.50, 2),
                'estimatedDays' => '2-4 Business Days',
                'badge' => 'Reliable',
            ],
            [
                'id' => 'ss_fedex_express',
                'carrierCode' => 'fedex',
                'carrierName' => 'FedEx Express',
                'serviceCode' => 'fedex_2day',
                'serviceName' => 'FedEx 2-Day Air Express',
                'totalCost' => $expressCost,
                'estimatedDays' => '1-2 Business Days',
                'badge' => 'Fastest',
            ]
        ];
    }
}
