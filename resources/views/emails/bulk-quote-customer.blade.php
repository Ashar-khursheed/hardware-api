<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Quote Request Received</title>
<style>
  /* ── Base ── */
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Segoe UI',Arial,sans-serif; -webkit-text-size-adjust:100%; }
  table { border-collapse:collapse; mso-table-lspace:0; mso-table-rspace:0; }

  /* ── Layout ── */
  .email-wrapper { width:100%; background:#f0f2f5; padding:20px 0; }
  .wrap { max-width:600px; margin:0 auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.09); }

  /* ── Header ── */
  .header { background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%); padding:40px 28px; text-align:center; }
  .icon-circle { width:64px; height:64px; background:#ff6600; border-radius:50%; margin:0 auto 16px; display:flex; align-items:center; justify-content:center; font-size:28px; }
  .header h1 { color:#fff; font-size:22px; font-weight:900; margin:0 0 6px; line-height:1.3; }
  .header p  { color:rgba(255,255,255,0.65); margin:0; font-size:13px; }

  /* ── Body ── */
  .body { padding:30px 28px; }
  .body h2 { font-size:19px; font-weight:800; color:#1a1a2e; margin:0 0 10px; }
  .body p  { font-size:14px; line-height:1.72; color:#555; margin:0 0 20px; }

  /* ── Summary table ── */
  .summary { background:#f7f8fc; border-radius:10px; padding:16px 20px; margin:20px 0; }
  .s-row { display:flex; justify-content:space-between; align-items:flex-start; padding:9px 0; border-bottom:1px solid #eef0f4; font-size:13px; gap:8px; }
  .s-row:last-child { border-bottom:none; padding-bottom:0; }
  .s-row .lbl { color:#888; font-weight:600; white-space:nowrap; }
  .s-row .val { color:#1a1a2e; font-weight:700; text-align:right; word-break:break-word; }

  /* ── Perks grid — desktop 2 col ── */
  .perks { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin:20px 0; }
  .perk { background:#fff9f5; border:1px solid #ffd9b8; border-radius:8px; padding:12px 10px; text-align:center; }
  .perk .emoji { font-size:20px; display:block; margin-bottom:4px; }
  .perk strong { display:block; font-size:12px; font-weight:800; color:#1a1a2e; }
  .perk span   { font-size:11px; color:#888; }

  /* ── CTA ── */
  .cta { display:block; width:fit-content; margin:8px auto 0; background:#ff6600; color:#fff; text-decoration:none; font-weight:700; font-size:14px; padding:14px 36px; border-radius:9px; }

  /* ── Footer ── */
  .footer { background:#1a1a2e; padding:22px 28px; text-align:center; }
  .footer p { color:rgba(255,255,255,0.45); font-size:12px; margin:4px 0; }
  .footer a { color:#ff6600; text-decoration:none; }

  /* ══ MOBILE ══ */
  @media only screen and (max-width: 600px) {
    .email-wrapper { padding:0 !important; }
    .wrap { border-radius:0 !important; box-shadow:none !important; }

    /* Header */
    .header { padding:28px 16px !important; }
    .header h1 { font-size:18px !important; }
    .icon-circle { width:54px !important; height:54px !important; font-size:22px !important; }

    /* Body */
    .body { padding:20px 16px !important; }
    .body h2 { font-size:17px !important; }

    /* Summary rows — label stacks above value */
    .s-row { flex-direction:column !important; align-items:flex-start !important; gap:2px !important; }
    .s-row .val { text-align:left !important; font-size:14px !important; }

    /* Perks — still 2 col but smaller padding */
    .perks { gap:8px !important; }
    .perk { padding:10px 6px !important; }

    /* CTA — full width */
    .cta {
      display:block !important;
      width:100% !important;
      box-sizing:border-box !important;
      text-align:center !important;
      padding:14px 16px !important;
      border-radius:8px !important;
    }

    /* Footer */
    .footer { padding:16px !important; }
    .footer p { font-size:11px !important; }
  }
</style>
</head>
<body>
<div class="email-wrapper">
<div class="wrap">

  {{-- Header --}}
  <div class="header">
    <div class="icon-circle">✓</div>
    <h1>Quote Request Received!</h1>
    <p>We'll be in touch within 2 business hours</p>
  </div>

  {{-- Body --}}
  <div class="body">
    <h2>Hi {{ $quote->full_name }},</h2>
    <p>
      Thank you for reaching out to <strong>Hardware Box</strong>. We've received your bulk quote request
      and our procurement specialists are already working on it. Expect a personalized, competitive
      quote in your inbox very shortly.
    </p>

    {{-- Order Summary --}}
    <div class="summary">
      <div class="s-row">
        <span class="lbl">Part # / Product</span>
        <span class="val">{{ $quote->part_number }}</span>
      </div>
      <div class="s-row">
        <span class="lbl">Quantity</span>
        <span class="val">{{ $quote->quantity }}</span>
      </div>
      @if($quote->org_name)
      <div class="s-row">
        <span class="lbl">Organization</span>
        <span class="val">{{ $quote->org_name }}</span>
      </div>
      @endif
      @if($quote->urgency)
      @php
        $labels = ['asap'=>'ASAP (1–3 days)','week'=>'Within a week','month'=>'Within a month','flexible'=>'Flexible'];
      @endphp
      <div class="s-row">
        <span class="lbl">Urgency</span>
        <span class="val">{{ $labels[$quote->urgency] }}</span>
      </div>
      @endif
      <div class="s-row">
        <span class="lbl">Submitted</span>
        <span class="val">{{ $quote->created_at->format('M d, Y g:i A') }}</span>
      </div>
    </div>

    {{-- Perks --}}
    <div class="perks">
      <div class="perk"><span class="emoji">⚡</span><strong>Fast Response</strong><span>Within 2 hours</span></div>
      <div class="perk"><span class="emoji">💰</span><strong>Best Price</strong><span>Volume discounts</span></div>
      <div class="perk"><span class="emoji">✅</span><strong>Genuine Stock</strong><span>Verified hardware</span></div>
      <div class="perk"><span class="emoji">🚚</span><strong>Fast Shipping</strong><span>Worldwide delivery</span></div>
    </div>

    <p>In the meantime, feel free to browse our full catalog or call us if you need immediate help.</p>
    <a class="cta" href="https://www.thehardwarebox.com">Browse Our Catalog →</a>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <p>Questions? <a href="tel:+18554837810">+1 (855) 483-7810</a> &nbsp;|&nbsp; <a href="mailto:hello@hardwarebox.com">hello@hardwarebox.com</a></p>
    <p>© {{ date('Y') }} Hardware Box. All rights reserved.</p>
    <p style="margin-top:8px;">
      <a href="https://www.thehardwarebox.com/privacy-policy">Privacy Policy</a>
    </p>
  </div>

</div>
</div>
</body>
</html>
