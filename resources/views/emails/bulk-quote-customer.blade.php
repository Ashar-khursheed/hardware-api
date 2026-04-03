<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Quote Request Received</title>
<style>
  /* ── Base ── */
  body { margin:0; padding:0; background:#f4f7f9; font-family:'Inter','Segoe UI',Helvetica,Arial,sans-serif; -webkit-text-size-adjust:100%; }
  table { border-collapse:collapse; mso-table-lspace:0; mso-table-rspace:0; }
  img { border:0; display:block; max-width:100%; }

  /* ── Layout ── */
  .email-wrapper { width:100%; background:#f4f7f9; padding:40px 0; }
  .wrap { max-width:600px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e1e8ed; }

  /* ── Header ── */
  .header { background:#ffffff; padding:40px; text-align:center; border-bottom: 2px solid #f4f7f9; }
  .logo { margin-bottom: 24px; }
  .logo img { width: 160px; margin: 0 auto; }
  .status-icon { width: 48px; height: 48px; background: #edfff4; color: #2ecc71; border-radius: 50%; display: flex; align-items: center; justify-content:center; margin: 0 auto 16px; font-size: 24px; border: 1px solid #cef7e0; }
  .header h1 { color:#1a202c; font-size:24px; font-weight:800; margin:0; letter-spacing:-0.5px; }

  /* ── Body ── */
  .body { padding:40px; }
  .body h2 { font-size:20px; font-weight:700; color:#1a202c; margin:0 0 12px; }
  .body p { font-size:15px; color:#4a5568; line-height:1.6; margin:0 0 24px; }

  /* ── Summary ── */
  .summary { background:#f8fafc; border-radius:12px; padding:24px; margin-bottom:32px; border: 1px solid #edf2f7; }
  .s-row { display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid #edf2f7; }
  .s-row:last-child { border-bottom:none; }
  .s-lbl { color:#718096; font-size:13px; font-weight:600; }
  .s-val { color:#2d3748; font-size:13px; font-weight:700; text-align:right; }

  /* ── Perks ── */
  .perks { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 32px; }
  .perk { background:#fff5f5; border: 1px solid #fed7d7; border-radius:10px; padding:16px; text-align:center; }
  .perk-icon { font-size: 20px; margin-bottom: 8px; display: block; }
  .perk-title { font-size:12px; font-weight:800; color:#1a202c; display:block; margin-bottom:2px; }
  .perk-text { font-size:11px; color:#718096; }

  /* ── CTA ── */
  .cta-wrap { text-align:center; }
  .btn-cta { display:inline-block; background:#ff5050; color:#ffffff !important; text-decoration:none; font-weight:700; font-size:15px; padding:16px 40px; border-radius:12px; }

  /* ── Footer ── */
  .footer { background:#1a202c; padding:40px; text-align:center; color:#ffffff; }
  .footer p { font-size:13px; opacity:0.7; margin:8px 0; }
  .footer a { color:#ff5050; text-decoration:none; font-weight:600; }

  @media (max-width: 600px) {
    .email-wrapper { padding:0; }
    .wrap { border-radius:0; border:none; }
    .body { padding:32px 20px; }
    .perks { grid-template-columns: 1fr; }
    .btn-cta { display:block; width:100%; box-sizing:border-box; }
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
    <div class="status-icon">✓</div>
    <h1>Quote Request Received</h1>
  </div>

  <div class="body">
    <h2>Hi {{ $quote->full_name }},</h2>
    <p>
      Thank you for choosing <strong>Hardware Box</strong>. We've received your request for a bulk quote. Our team is currently reviewing the specifications and we will get back to you with a competitive proposal within <strong>2 business hours</strong>.
    </p>

    <div class="summary">
      <div class="s-row">
        <span class="s-lbl">Part # / Product</span>
        <span class="s-val">{{ $quote->part_number }}</span>
      </div>
      <div class="s-row">
        <span class="s-lbl">Quantity</span>
        <span class="s-val">{{ $quote->quantity }}</span>
      </div>
      @if($quote->org_name)
      <div class="s-row">
        <span class="s-lbl">Organization</span>
        <span class="s-val">{{ $quote->org_name }}</span>
      </div>
      @endif
      @if($quote->urgency)
      <div class="s-row">
        <span class="s-lbl">Urgency</span>
        <span class="s-val">
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
        </span>
      </div>
      @endif
    </div>

    <div class="perks">
      <div class="perk"><span class="perk-icon">⚡</span><strong class="perk-title">Fast Response</strong><span class="perk-text">Within 2 business hours</span></div>
      <div class="perk"><span class="perk-icon">💰</span><strong class="perk-title">Volume Pricing</strong><span class="perk-text">Best deals on bulk orders</span></div>
      <div class="perk"><span class="perk-icon">🚚</span><strong class="perk-title">Express Shipping</strong><span class="perk-text">Fast global delivery</span></div>
      <div class="perk"><span class="perk-icon">💎</span><strong class="perk-title">Support</strong><span class="perk-text">Dedicated account manager</span></div>
    </div>

    <div class="cta-wrap">
      <a href="https://thehardwarebox.com" class="btn-cta">Explore Full Catalog →</a>
    </div>
  </div>

  <div class="footer">
    <p>Questions? Call <a href="tel:+18554837810" style="color:white;opacity:1;">+1 (855) 483-7810</a></p>
    <p>Email: <a href="mailto:support@thehardwarebox.com">support@thehardwarebox.com</a></p>
    <p>© {{ date('Y') }} Hardware Box. All rights reserved.</p>
  </div>
</div>
</div>
</body>
</html>
