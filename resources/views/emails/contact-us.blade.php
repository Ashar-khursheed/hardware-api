<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>New Contact Message</title>
<style>
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter', sans-serif; }
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:600px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
  .header { padding:32px; text-align:center; border-bottom: 2px solid #f4f7f9; }
  .logo img { width: 180px; }
  .body { padding:40px; }
  .body h2 { font-size:20px; color:#1a202c; margin-bottom:24px; }
  .info-grid { background:#f8fafc; border-radius:12px; padding:24px; border: 1px solid #edf2f7; }
  .info-row { padding:12px 0; border-bottom:1px solid #edf2f7; font-size:14px; }
  .info-row:last-child { border-bottom:none; }
  .info-label { color:#718096; font-weight:700; width:100px; display:inline-block; }
  .info-value { color:#2d3748; font-weight:600; }
  .message-box { margin-top:20px; padding:20px; background:#fff; border:1px solid #edf2f7; border-radius:8px; font-size:14px; color:#4a5568; line-height:1.6; }
</style>
</head>
<body>
<div class="email-wrapper">
<div class="wrap">
  <div class="header">
    <img src="https://d3243ix3g2hwoc.cloudfront.net/24995/hard.jpg" alt="Logo"/>
  </div>
  <div class="body">
    <h2>New Inbound Inquiry</h2>
    <div class="info-grid">
      <div class="info-row"><span class="info-label">Name:</span><span class="info-value">{{ $contact->name }}</span></div>
      <div class="info-row"><span class="info-label">Email:</span><span class="info-value">{{ $contact->email }}</span></div>
      <div class="info-row"><span class="info-label">Phone:</span><span class="info-value">{{ $contact->phone }}</span></div>
      <div class="info-row"><span class="info-label">Subject:</span><span class="info-value">{{ $contact->subject }}</span></div>
    </div>
    <div class="message-box">
      <strong>Message:</strong><br>
      {{ $contact->message }}
    </div>
  </div>
</div>
</div>
</body>
</html>
