<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Quote Request Received</title>
<style>
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Segoe UI',Arial,sans-serif; }
  .wrap { max-width:600px; margin:30px auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.09); }
  .header { background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%); padding:44px 40px; text-align:center; }
  .icon-circle { width:68px; height:68px; background:#ff6600; border-radius:50%; margin:0 auto 18px; display:flex; align-items:center; justify-content:center; font-size:30px; }
  .header h1 { color:#fff; font-size:24px; font-weight:900; margin:0 0 6px; }
  .header p  { color:rgba(255,255,255,0.65); margin:0; font-size:14px; }
  .body { padding:38px 44px; }
  .body h2 { font-size:20px; font-weight:800; color:#1a1a2e; margin:0 0 10px; }
  .body p  { font-size:14px; line-height:1.72; color:#555; margin:0 0 22px; }
  .summary { background:#f7f8fc; border-radius:10px; padding:20px 24px; margin:22px 0; }
  .s-row { display:flex; justify-content:space-between; padding:9px 0; border-bottom:1px solid #eef0f4; font-size:13px; }
  .s-row:last-child { border-bottom:none; padding-bottom:0; }
  .s-row .lbl { color:#888; font-weight:600; }
  .s-row .val { color:#1a1a2e; font-weight:700; text-align:right; max-width:60%; }
  .perks { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin:22px 0; }
  .perk { background:#fff9f5; border:1px solid #ffd9b8; border-radius:8px; padding:14px 12px; text-align:center; }
  .perk .emoji { font-size:20px; display:block; margin-bottom:4px; }
  .perk strong { display:block; font-size:12px; font-weight:800; color:#1a1a2e; }
  .perk span   { font-size:11px; color:#888; }
  .cta { display:block; width:fit-content; margin:8px auto 0; background:#ff6600; color:#fff; text-decoration:none; font-weight:700; font-size:14px; padding:14px 36px; border-radius:9px; }
  .footer { background:#1a1a2e; padding:24px 40px; text-align:center; }
  .footer p { color:rgba(255,255,255,0.45); font-size:12px; margin:4px 0; }
  .footer a { color:#ff6600; text-decoration:none; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <div class="icon-circle">✓</div>
    <h1>Quote Request Received!</h1>
    <p>We'll be in touch within 2 business hours</p>
  </div>
  <div class="body">
    <h2>Hi {{ $quote->full_name }},</h2>
    <p>
      Thank you for reaching out to <strong>Hardware Box</strong>. We've received your bulk quote request
      and our procurement specialists are already working on it. Expect a personalized, competitive
      quote in your inbox very shortly.
    </p>
    <div class="summary">
      <div class="s-row"><span class="lbl">Part # / Product</span><span class="val">{{ $quote->part_number }}</span></div>
      <div class="s-row"><span class="lbl">Quantity</span><span class="val">{{ $quote->quantity }}</span></div>
      @if($quote->org_name)
      <div class="s-row"><span class="lbl">Organization</span><span class="val">{{ $quote->org_name }}</span></div>
      @endif
      @if($quote->urgency)
      @php
        $labels = ['asap'=>'ASAP (1–3 days)','week'=>'Within a week','month'=>'Within a month','flexible'=>'Flexible'];
      @endphp
      <div class="s-row"><span class="lbl">Urgency</span><span class="val">{{ $labels[$quote->urgency] }}</span></div>
      @endif
      <div class="s-row"><span class="lbl">Submitted</span><span class="val">{{ $quote->created_at->format('M d, Y — g:i A') }}</span></div>
    </div>
    <div class="perks">
      <div class="perk"><span class="emoji">⚡</span><strong>Fast Response</strong><span>Within 2 hours</span></div>
      <div class="perk"><span class="emoji">💰</span><strong>Best Price</strong><span>Volume discounts</span></div>
      <div class="perk"><span class="emoji">✅</span><strong>Genuine Stock</strong><span>Verified hardware</span></div>
      <div class="perk"><span class="emoji">🚚</span><strong>Fast Shipping</strong><span>Worldwide delivery</span></div>
    </div>
    <p>In the meantime, feel free to browse our full catalog or call us if you need immediate help.</p>
    <a class="cta" href="https://www.thehardwarebox.com">Browse Our Catalog →</a>
  </div>
  <div class="footer">
    <p>Questions? <a href="tel:+18554837810">+1 (855) 483-7810</a> &nbsp;|&nbsp; <a href="mailto:hello@hardwarebox.com">hello@hardwarebox.com</a></p>
    <p>© {{ date('Y') }} Hardware Box. All rights reserved.</p>
    <p style="margin-top:10px;">
      <a href="https://www.thehardwarebox.com/privacy-policy">Privacy Policy</a>
    </p>
  </div>
</div>
</body>
</html>
