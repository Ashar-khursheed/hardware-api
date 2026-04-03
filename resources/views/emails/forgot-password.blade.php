<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Reset Password</title>
<style>
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter', sans-serif; }
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:500px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); text-align:center; }
  .header { padding:32px; border-bottom: 2px solid #f4f7f9; }
  .logo img { width: 180px; }
  .body { padding:40px; }
  .body h2 { font-size:22px; color:#1a202c; margin-bottom:12px; }
  .body p { font-size:15px; color:#718096; line-height:1.6; margin-bottom:24px; }
  .otp-box { background:#f8fafc; border: 2px dashed #edf2f7; border-radius:12px; padding:32px; margin-bottom:24px; }
  .otp { font-size:48px; font-weight:800; color:#ff5050; letter-spacing:8px; }
</style>
</head>
<body>
<div class="email-wrapper">
<div class="wrap">
  <div class="header">
    <img src="https://d3243ix3g2hwoc.cloudfront.net/24995/hard.jpg" alt="Logo"/>
  </div>
  <div class="body">
    <h2>Reset Password</h2>
    <p>You requested a password reset. Use the verification code below to secure your account:</p>
    <div class="otp-box">
      <div class="otp">{{ $token }}</div>
    </div>
    <p style="font-size:13px; color:#a0aec0;">If you didn't request this, please ignore this email.</p>
  </div>
</div>
</div>
</body>
</html>
