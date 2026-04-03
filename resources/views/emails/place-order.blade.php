<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Order Confirmation - {{ $order->order_number }}</title>
<style>
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter','Segoe UI',Helvetica,Arial,sans-serif; }
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:600px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
  .header { padding:40px; text-align:center; border-bottom: 2px solid #f4f7f9; }
  .logo img { width: 180px; margin-bottom: 20px; }
  .status-badge { display:inline-block; background:#fff5f5; color:#ff5050; font-size:12px; font-weight:700; padding:6px 16px; border-radius:50px; text-transform:uppercase; border: 1px solid #fed7d7; }
  
  .body { padding:40px; }
  .body h2 { font-size:22px; color:#1a202c; margin:0 0 16px; }
  .body p { font-size:15px; color:#4a5568; line-height:1.6; margin:0 0 24px; }
  
  .order-info { background:#f8fafc; border-radius:12px; padding:24px; margin-bottom:32px; border: 1px solid #edf2f7; }
  .info-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #edf2f7; font-size:14px; }
  .info-row:last-child { border-bottom:none; }
  .info-label { color:#718096; font-weight:600; }
  .info-value { color:#2d3748; font-weight:700; }

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
    <span class="status-badge">Order Confirmed</span>
  </div>

  <div class="body">
    <h2>Hello {{ $order->consumer['name'] }},</h2>
    <p>
      We're excited to confirm your order! Our team is already preparing your items for shipment. You'll receive another notification with tracking details as soon as it leaves our warehouse.
    </p>

    <div class="order-info">
      <div class="info-row">
        <span class="info-label">Order Number</span>
        <span class="info-value">#{{ $order->order_number }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Payment Status</span>
        <span class="info-value">{{ ucfirst($order->payment_status) }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Order Status</span>
        <span class="info-value">{{ $order->order_status->name }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Total Amount</span>
        <span class="info-value">{{ number_format($order->total, 2) }}</span>
      </div>
    </div>

    <p>Thank you for choosing Hardware Box. We appreciate your business!</p>
  </div>

  <div class="footer">
    <p>© {{ date('Y') }} Hardware Box &nbsp;·&nbsp; <a href="https://thehardwarebox.com">Visit Store</a></p>
    <p>Support: <a href="mailto:support@thehardwarebox.com">support@thehardwarebox.com</a></p>
  </div>
</div>
</div>
</body>
</html>
