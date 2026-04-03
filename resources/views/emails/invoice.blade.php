@use('App\Helpers\Helpers')
@php
$currencyCode = Helpers::getDefaultCurrencyCode();
$currencySymbol = ($currencyCode == 'INR') ? "Rs." : Helpers::getDefaultCurrencySymbol();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Invoice - {{ $order->order_number }}</title>
<style>
    body { font-family: 'Inter', 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0; background: #f8fafc; color: #1e293b; }
    .invoice-container { max-width: 850px; margin: 40px auto; background: #fff; padding: 50px; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.05); }
    
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 50px; border-bottom: 2px solid #f1f5f9; padding-bottom: 30px; }
    .logo-area img { width: 180px; }
    .invoice-title { text-align: right; }
    .invoice-title h1 { margin: 0; font-size: 32px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; }
    .invoice-title p { margin: 5px 0 0; color: #64748b; font-weight: 600; font-size: 14px; }

    .details-grid { display: flex; gap: 40px; margin-bottom: 50px; }
    .detail-col { flex: 1; }
    .detail-col h3 { font-size: 12px; text-transform: uppercase; color: #94a3b8; letter-spacing: 1px; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 5px; }
    .detail-col p { margin: 4px 0; font-size: 13px; line-height: 1.6; color: #334155; }
    .text-bold { font-weight: 700; color: #0f172a; }

    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
    .items-table th { background: #f8fafc; padding: 15px; text-align: left; font-size: 12px; text-transform: uppercase; color: #64748b; border-bottom: 2px solid #e2e8f0; }
    .items-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; }
    .items-table tr:last-child td { border-bottom: none; }

    .summary-section { display: flex; justify-content: flex-end; margin-top: 20px; }
    .summary-box { width: 300px; background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
    .summary-row.total { border-top: 1px solid #cbd5e1; margin-top: 10px; padding-top: 15px; font-size: 18px; font-weight: 800; color: #ff5050; }

    .footer { text-align: center; margin-top: 50px; padding-top: 30px; border-top: 1px solid #f1f5f9; font-size: 12px; color: #94a3b8; line-height: 1.6; }
    .footer a { color: #ff5050; text-decoration: none; }

    @media print {
        body { background: #fff; }
        .invoice-container { margin: 0; box-shadow: none; width: 100%; padding: 20px; }
    }
</style>
</head>
<body>
<div class="invoice-container">
    <div class="header" style="width: 100%; display: table; content: '';">
        <div class="logo-area" style="display: table-cell; vertical-align: top;">
            <img src="https://d3243ix3g2hwoc.cloudfront.net/24995/hard.jpg" alt="Logo"/>
        </div>
        <div class="invoice-title" style="display: table-cell; vertical-align: top; text-align: right;">
            <h1>Invoice</h1>
            <p># {{ $order->order_number }}</p>
        </div>
    </div>

    <div class="details-grid" style="width: 100%; display: table;">
        <div class="detail-col" style="display: table-cell; width: 33%;">
            <h3>Customer</h3>
            <p class="text-bold">{{ $order->consumer['name'] }}</p>
            <p>{{ $order->consumer['email'] }}</p>
            <p>+{{ $order->consumer['country_code'] }} {{ $order->consumer['phone'] }}</p>
        </div>
        <div class="detail-col" style="display: table-cell; width: 33%;">
            <h3>Billing Address</h3>
            <p>{{ $order->billing_address['street'] }}</p>
            <p>{{ $order->billing_address['city'] }}, {{ $order->billing_address['pincode'] }}</p>
            @if (isset($order->billing_address['state']) && isset($order->billing_address['country']))
                <p>{{ $order->billing_address['state']['name'] }}, {{ $order->billing_address['country']['name'] }}</p>
            @endif
        </div>
        @if (!$order->is_digital_only)
        <div class="detail-col" style="display: table-cell; width: 33%;">
            <h3>Shipping Address</h3>
            <p>{{ $order->shipping_address['street'] }}</p>
            <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['pincode'] }}</p>
            @if (isset($order->shipping_address['state']) && isset($order->shipping_address['country']))
                <p>{{ $order->shipping_address['state']['name'] }}, {{ $order->shipping_address['country']['name'] }}</p>
            @endif
        </div>
        @endif
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%">Product Description</th>
                <th style="text-align: center">Price</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->products as $product)
            <tr>
                <td>
                    <span class="text-bold">{{ $product->name }}</span>
                </td>
                <td style="text-align: center">{{ $currencySymbol }} {{ number_format($product->pivot->single_price, 2) }}</td>
                <td style="text-align: center">{{ $product->pivot->quantity }}</td>
                <td style="text-align: right">{{ $currencySymbol }} {{ number_format($product->pivot->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section" style="width: 100%; display: table;">
        <div style="display: table-cell; width: 60%;">
            <div style="padding: 20px; background: #fff9f5; border-radius: 8px; border: 1px solid #ffeada; font-size: 13px; color: #c2410c;">
                <strong>Payment Method:</strong> {{ $order->payment_method }}<br>
                <strong>Date:</strong> {{ $order->created_at->format("M d, Y") }}
            </div>
        </div>
        <div style="display: table-cell; width: 40%;">
            <div class="summary-box" style="float: right;">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>{{ $currencySymbol }} {{ number_format($order->amount, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Tax</span>
                    <span>{{ $currencySymbol }} {{ number_format($order->tax_total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>{{ $currencySymbol }} {{ number_format($order->shipping_total, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span>Grand Total</span>
                    <span>{{ $currencySymbol }} {{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for your business! For any queries, contact us at <strong>support@thehardwarebox.com</strong></p>
        <p>Hardware Box &nbsp;·&nbsp; <a href="https://thehardwarebox.com">thehardwarebox.com</a></p>
    </div>
</div>
</body>
</html>
