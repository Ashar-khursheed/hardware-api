<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>New Bulk Quote Request</title>
<style>
  /* ── Base ── */
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter','Segoe UI',Helvetica,Arial,sans-serif; -webkit-text-size-adjust:100%; }
  table { border-collapse:collapse; mso-table-lspace:0; mso-table-rspace:0; }
  img { border:0; display:block; max-width:100%; }

  /* ── Layout ── */
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:650px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e1e8ed; }

  /* ── Header ── */
  .header { background:#ffffff; padding:32px 40px; text-align:center; border-bottom: 2px solid #f4f7f9; }
  .logo { margin-bottom: 24px; }
  .logo img { width: 180px; margin: 0 auto; }
  .header h1 { color:#1a202c; font-size:22px; font-weight:800; margin:0 0 8px; letter-spacing:-0.5px; }
  .badge { display:inline-block; background:#ff5050; color:#fff; font-size:12px; font-weight:700; padding:6px 16px; border-radius:50px; text-transform:uppercase; letter-spacing:0.5px; }

  /* ── Body ── */
  .body { padding:40px; }
  .section-title { font-size:13px; font-weight:800; color:#ff5050; letter-spacing:1.2px; text-transform:uppercase; margin:0 0 20px; border-bottom: 2px solid #fff5f5; padding-bottom: 8px; }

  .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 32px; }
  .info-card { background:#f8fafc; border-radius:12px; padding:16px 20px; border: 1px solid #edf2f7; }
  .info-label { font-size:11px; font-weight:700; color:#718096; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px; }
  .info-value { font-size:14px; font-weight:600; color:#2d3748; line-height:1.5; }

  /* ── Action Buttons ── */
  .action-wrap { text-align:center; margin-top:32px; padding-top:32px; border-top: 1px solid #edf2f7; }
  .btn-primary { display:inline-block; background:#1a202c; color:#ffffff !important; text-decoration:none; font-weight:700; font-size:15px; padding:16px 40px; border-radius:12px; transition: all 0.2s ease; }

  /* ── Footer ── */
  .footer { background:#f8fafc; padding:32px 40px; text-align:center; font-size:13px; color:#a0aec0; border-top: 1px solid #edf2f7; }
  .footer a { color:#ff5050; text-decoration:none; font-weight:600; }

  @media (max-width: 600px) {
    .email-wrapper { padding:0; }
    .wrap { border-radius:0; border:none; }
    .info-grid { grid-template-columns: 1fr; gap: 12px; }
    .body { padding:24px 20px; }
    .header { padding:32px 20px; }
    .btn-primary { display:block; width:100%; box-sizing:border-box; }
  }
</style>
</head>
<body>
<div class="email-wrapper">
<div class="wrap">
  <div class="header">
    <div class="logo">
      <img src="https://d3243ix3g2hwoc.cloudfront.net/24995/hard.jpg" alt="Hardware Box Logo"/>
    </div>
    <h1>Bulk Quote Request</h1>
    <span class="badge">Attention Required</span>
  </div>

  <div class="body">
    <p class="section-title">Customer Information</p>
    <div class="info-grid">
      <div class="info-card">
        <div class="info-label">Full Name</div>
        <div class="info-value">{{ $quote->full_name }}</div>
      </div>
      <div class="info-card">
        <div class="info-label">Organization</div>
        <div class="info-value">{{ $quote->org_name ?: 'N/A' }}</div>
      </div>
      <div class="info-card">
        <div class="info-label">Email Address</div>
        <div class="info-value">{{ $quote->email }}</div>
      </div>
      <div class="info-card">
        <div class="info-label">Phone Number</div>
        <div class="info-value">{{ $quote->phone }}</div>
      </div>
    </div>

    <p class="section-title">Requirement Details</p>
    <div class="info-grid">
      <div class="info-card">
        <div class="info-label">Part # / Product</div>
        <div class="info-value">{{ $quote->part_number }}</div>
      </div>
      <div class="info-card">
        <div class="info-label">Quantity</div>
        <div class="info-value">{{ $quote->quantity }}</div>
      </div>
      <div class="info-card">
        <div class="info-label">Urgency Level</div>
        <div class="info-value">
          @php
            $labels = [
                'asap'     => 'ASAP (1–3 days)',
                'week'     => 'Within a week',
                'month'    => 'Within a month',
                'flexible' => 'Flexible',
                'future'   => 'Future Project'
            ];
          @endphp
          {{ $labels[$quote->urgency] ?? 'Standard' }}
        </div>
      </div>
      <div class="info-card">
        <div class="info-label">Submission Date</div>
        <div class="info-value">{{ $quote->created_at->format('M d, Y') }}</div>
      </div>
    </div>

    @if($quote->description)
    <p class="section-title">Additional Comments</p>
    <div class="info-card" style="margin-bottom: 32px;">
      <div class="info-value" style="font-weight: 400; color: #4a5568;">{{ $quote->description }}</div>
    </div>
    @endif

    <div class="action-wrap">
      <a href="mailto:{{ $quote->email }}" class="btn-primary">Reply & Send Quote</a>
    </div>
  </div>

  <div class="footer">
    <p>© Hardware Box &nbsp;·&nbsp; <a href="https://thehardwarebox.com">thehardwarebox.com</a></p>
    <p style="margin-top: 8px; font-size: 11px;">This is an automated system notification.</p>
  </div>
</div>
</div>
</body>
</html>
