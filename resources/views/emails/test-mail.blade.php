<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>SMTP Test Configuration</title>
<style>
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter', sans-serif; }
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:600px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
  .header { padding:32px; text-align:center; border-bottom: 2px solid #f4f7f9; }
  .logo img { width: 180px; }
  .body { padding:40px; }
  .status-tag { display:inline-block; background:#edfff4; color:#2ecc71; font-weight:700; padding:4px 12px; border-radius:4px; font-size:12px; margin-bottom:16px; }
  .info-table { width:100%; border-collapse:collapse; margin-top:20px; }
  .info-table td { padding:10px; border-bottom:1px solid #f1f5f9; font-size:13px; }
  .info-label { color:#94a3b8; font-weight:600; width:150px; }
  .info-value { color:#334155; font-family: 'Courier New', monospace; }
</style>
</head>
<body>
<div class="email-wrapper">
<div class="wrap">
  <div class="header">
    <img src="https://d3243ix3g2hwoc.cloudfront.net/24995/hard.jpg" alt="Logo"/>
  </div>
  <div class="body">
    <div class="status-tag">SMTP TEST SUCCESSFUL</div>
    <h2 style="margin:0 0 10px; font-size:20px; color:#1a202c;">Connection Verified</h2>
    <p style="font-size:14px; color:#64748b; margin:0;">The system successfully reached the mail server using the following configuration:</p>
    
    <table class="info-table">
      <tr><td class="info-label">Mailer</td><td class="info-value">{{ $request?->mail_mailer }}</td></tr>
      <tr><td class="info-label">Host</td><td class="info-value">{{ $request?->mail_host }}</td></tr>
      <tr><td class="info-label">Port</td><td class="info-value">{{ $request?->mail_port }}</td></tr>
      <tr><td class="info-label">Encryption</td><td class="info-value">{{ $request?->mail_encryption }}</td></tr>
      <tr><td class="info-label">Username</td><td class="info-value">{{ $request?->mail_username }}</td></tr>
    </table>
  </div>
</div>
</div>
</body>
</html>
