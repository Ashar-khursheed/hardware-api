@use('App\Helpers\Helpers')
@php
$currencyCode = Helpers::getDefaultCurrencyCode();
$currencySymbol = ($currencyCode == 'USD') ? "$" : Helpers::getDefaultCurrencySymbol();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Order Status Update</title>
<style>
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter', sans-serif; }
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:700px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
  .header { padding:32px; text-align:center; border-bottom: 2px solid #f4f7f9; }
  .logo img { width: 180px; }
  .body { padding:40px; }
  .body h2 { font-size:20px; color:#1a202c; margin-bottom:12px; }
  .body p { font-size:15px; color:#4a5568; line-height:1.6; margin-bottom:24px; }
  .status-change { background: #fff5f5; border: 1px solid #fed7d7; border-radius:12px; padding:24px; text-align:center; margin-bottom: 32px; }
  .status-label { font-size:13px; color:#718096; display:block; margin-bottom:4px; text-transform: uppercase; font-weight: 700; }
  .new-status { font-size:28px; font-weight:800; color:#ff5050; display:block; }

  .items-table { width: 100%; border-collapse: collapse; margin: 32px 0; }
  .items-table th { background: #f8fafc; padding: 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: #718096; border-bottom: 2px solid #edf2f7; }
  .items-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #2d3748; }

  .summary-box { width: 280px; margin-left: auto; margin-top: 24px; }
  .summary-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; color: #4a5568; }
  .summary-row.total { border-top: 1px solid #edf2f7; margin-top: 8px; padding-top: 12px; font-weight: 800; color: #ff5050; font-size: 18px; }

  .footer { padding:32px; background:#1a202c; color:#fff; text-align:center; font-size:13px; }
  .footer a { color:#ff5050; text-decoration:none; }
</style>
</head>
<body>
<div class="email-wrapper">
<div class="wrap">
  <div class="header">
    <img src="https://d3243ix3g2hwoc.cloudfront.net/24995/hard.jpg" alt="Logo"/>
  </div>
  <div class="body">
    <h2>Order Status Update</h2>
    <p>Hi {{ $order->consumer['name'] }}, great news! The status of your order <strong>#{{ $order->order_number }}</strong> has changed.</p>
    
    <div class="status-change">
      <span class="status-label">Current Status</span>
      <span class="new-status">{{ $order->order_status->name }}</span>
    </div>

    <h3>Review Your Items</h3>
    <table class="items-table">
      <thead>
        <tr>
          <th>Product</th>
          <th style="text-align: center;">Qty</th>
          <th style="text-align: right;">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->products as $product)
        <tr>
          <td>
            <strong style="color: #1a202c;">{{ $product->name }}</strong>
            @if(isset($product->pivot->variation_options))
              <br><small style="color: #718096;">{{ $product->pivot->variation_options }}</small>
            @endif
          </td>
          <td style="text-align: center;">{{ $product->pivot->quantity }}</td>
          <td style="text-align: right;">{{ $currencySymbol }} {{ number_format($product->pivot->subtotal, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="summary-box">
      <div class="summary-row">
        <span>Subtotal</span>
        <span>{{ $currencySymbol }} {{ number_format($order->amount, 2) }}</span>
      </div>
      <div class="summary-row">
        <span>Shipping</span>
        <span>{{ $currencySymbol }} {{ number_format($order->shipping_total, 2) }}</span>
      </div>
      <div class="summary-row total">
        <span>Total</span>
        <span>{{ $currencySymbol }} {{ number_format($order->total, 2) }}</span>
      </div>
    </div>
  </div>
  <div class="footer">
    <p>© {{ date('Y') }} Hardware Box &nbsp;·&nbsp; <a href="https://thehardwarebox.com">thehardwarebox.com</a></p>
  </div>
</div>
</div>
</body>
</html>
