<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShipStationService;
use Illuminate\Http\Request;

class ShipStationController extends Controller
{
    protected ShipStationService $shipStationService;

    public function __construct(ShipStationService $shipStationService)
    {
        $this->shipStationService = $shipStationService;
    }

    /**
     * Get live rates for checkout
     */
    public function getRates(Request $request)
    {
        try {
            $rates = $this->shipStationService->getRates($request->all());

            return response()->json([
                'success' => true,
                'rates' => $rates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'rates' => $this->shipStationService->getDynamicFallbackRates('CA', '90210', 32, 0)
            ], 200);
        }
    }
}
