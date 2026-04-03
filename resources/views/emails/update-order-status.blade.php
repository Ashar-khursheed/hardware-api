<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Order Status Update</title>
<style>
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter', sans-serif; }
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:600px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
  .header { padding:32px; text-align:center; border-bottom: 2px solid #f4f7f9; }
  .logo img { width: 180px; }
  .body { padding:40px; }
  .body h2 { font-size:20px; color:#1a202c; margin-bottom:12px; }
  .body p { font-size:15px; color:#4a5568; line-height:1.6; margin-bottom:24px; }
  .status-change { background:#f8fafc; border: 1px solid #edf2f7; border-radius:12px; padding:24px; text-align:center; }
  .old-status { font-size:12px; text-decoration:line-through; color:#a0aec0; }
  .new-status { font-size:24px; font-weight:800; color:#ff5050; margin-top:8px; display:block; }
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
    <p>Hi {{ $order->consumer['name'] }}, we're updating you on your order <strong>#{{ $order->order_number }}</strong>.</p>
    <div class="status-change">
      <span style="font-size:13px; color:#718096; display:block; margin-bottom:4px;">Current Status</span>
      <span class="new-status">{{ $order->order_status->name }}</span>
    </div>
    <p style="margin-top:24px;">If you have any questions, our support team is happy to help.</p>
  </div>
  <div class="footer">
    <p>© {{ date('Y') }} Hardware Box &nbsp;·&nbsp; <a href="https://thehardwarebox.com">thehardwarebox.com</a></p>
  </div>
</div>
</div>
</body>
</html>
