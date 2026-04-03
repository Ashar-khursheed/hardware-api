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
<title>Order Cancelled - {{ $order->order_number }}</title>
<style>
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter','Segoe UI',Helvetica,Arial,sans-serif; }
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:700px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
  .header { padding:40px; text-align:center; border-bottom: 2px solid #f4f7f9; }
  .logo img { width: 180px; margin-bottom: 20px; }
  .status-badge { display:inline-block; background:#fff5f5; color:#ff5050; font-size:12px; font-weight:700; padding:6px 16px; border-radius:50px; text-transform:uppercase; border: 1px solid #fed7d7; }
  
  .body { padding:40px; }
  .body h2 { font-size:22px; color:#1a202c; margin:0 0 16px; }
  .body p { font-size:15px; color:#4a5568; line-height:1.6; margin:0 0 24px; }
  
  .order-info { background:#f8fafc; border-radius:12px; padding:24px; margin-bottom:32px; border: 1px solid #edf2f7; }
  .info-grid { width: 100%; display: table; }
  .info-col { display: table-cell; width: 50%; font-size: 14px; color: #4a5568; line-height: 1.6; vertical-align: top; }
  .info-label { font-weight: 700; color: #1a202c; margin-bottom: 4px; display: block; }

  .items-table { width: 100%; border-collapse: collapse; margin: 32px 0; }
  .items-table th { background: #f8fafc; padding: 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: #718096; border-bottom: 2px solid #edf2f7; }
  .items-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #2d3748; }

  .summary-box { width: 280px; margin-left: auto; margin-top: 24px; }
  .summary-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; color: #4a5568; }
  .summary-row.total { border-top: 1px solid #edf2f7; margin-top: 8px; padding-top: 12px; font-weight: 800; color: #ff5050; font-size: 18px; }

  .footer { background:#1a202c; padding:40px; text-align:center; color:#ffffff; }
  .footer p { font-size:13px; opacity:0.7; margin:8px 0; }
  .footer a { color:#ff5050; text-decoration:none; }
</style>
</head>
<body>
<div class="email-wrapper">
<div class="wrap">
  <div class="header">
    <div class="logo">
      <img src="https://d3243ix3g2hwoc.cloudfront.net/24995/hard.jpg" alt="Hardware Box Logo"/>
    </div>
    <span class="status-badge" style="background:#fef2f2; color:#dc2626;">Order Cancelled</span>
  </div>

  <div class="body">
    <h2>Order #{{ $order->order_number }} Update</h2>
    <p>Hi {{ $order->consumer['name'] }}, this email is to confirm that your order <strong>#{{ $order->order_number }}</strong> has been cancelled.</p>

    <div class="order-info">
      <div class="info-grid">
        <div class="info-col">
          <span class="info-label">Order Number: </span>
          #{{ $order->order_number }}
        </div>
        <div class="info-col" style="text-align: right;">
          <span class="info-label">Date: </span>
          {{ $order->created_at->format('M d, Y') }}
        </div>
      </div>
    </div>

    <h3>Order Details</h3>
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
        <span>Order Total</span>
        <span>{{ $currencySymbol }} {{ number_format($order->total, 2) }}</span>
      </div>
    </div>
  </div>

  <div class="footer">
    <p>© {{ date('Y') }} Hardware Box &nbsp;·&nbsp; <a href="https://thehardwarebox.com">Visit Store</a></p>
    <p>Support: <a href="mailto:support@thehardwarebox.com">support@thehardwarebox.com</a></p>
  </div>
</div>
</div>
</body>
</html>
