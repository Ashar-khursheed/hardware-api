<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>New Bulk Quote Request</title>
<style>
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Segoe UI',Arial,sans-serif; }
  .wrap { max-width:640px; margin:30px auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.09); }
  .header { background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%); padding:36px 44px; text-align:center; }
  .header h1 { color:#ffffff; font-size:22px; font-weight:800; margin:0 0 8px; }
  .badge { display:inline-block; background:#ff6600; color:#fff; font-size:11px; font-weight:700; letter-spacing:1px; padding:5px 16px; border-radius:20px; text-transform:uppercase; }
  .body { padding:36px 44px; }
  .section-title { font-size:13px; font-weight:800; color:#ff6600; letter-spacing:1px; text-transform:uppercase; margin:0 0 16px; padding-bottom:8px; border-bottom:2px solid #fff0e6; }
  .row { display:flex; gap:16px; margin-bottom:14px; }
  .cell { flex:1; }
  .cell .lbl { font-size:11px; font-weight:700; color:#999; letter-spacing:.5px; text-transform:uppercase; margin-bottom:4px; }
  .cell .val { font-size:14px; font-weight:600; color:#1a1a2e; background:#f7f8fc; border-left:3px solid #ff6600; border-radius:0 6px 6px 0; padding:9px 13px; }
  .notes-box { background:#f7f8fc; border-left:3px solid #ff6600; border-radius:0 8px 8px 0; padding:14px 16px; font-size:14px; color:#444; line-height:1.65; }
  .divider { border:none; border-top:1px solid #f0f0f0; margin:24px 0; }
  .reply-btn { display:inline-block; background:#ff6600; color:#fff; text-decoration:none; font-weight:700; font-size:14px; padding:13px 32px; border-radius:8px; margin-top:8px; }
  .footer { background:#f7f8fc; padding:20px 44px; text-align:center; font-size:12px; color:#aaa; }
  .footer a { color:#ff6600; text-decoration:none; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>🔔 New Bulk Quote Request</h1>
    <span class="badge">Action Required</span>
  </div>
  <div class="body">
    <p class="section-title">Customer Details</p>
    <div class="row">
      <div class="cell">
        <div class="lbl">Full Name</div>
        <div class="val">{{ $quote->full_name }}</div>
      </div>
      <div class="cell">
        <div class="lbl">Organization</div>
        <div class="val">{{ $quote->org_name ?: '—' }}</div>
      </div>
    </div>
    <div class="row">
      <div class="cell">
        <div class="lbl">Email</div>
        <div class="val">{{ $quote->email }}</div>
      </div>
      <div class="cell">
        <div class="lbl">Phone</div>
        <div class="val">{{ $quote->phone }}</div>
      </div>
    </div>
    <hr class="divider"/>
    <p class="section-title">Quote Details</p>
    <div class="row">
      <div class="cell">
        <div class="lbl">Part # / Product</div>
        <div class="val">{{ $quote->part_number }}</div>
      </div>
      <div class="cell">
        <div class="lbl">Quantity</div>
        <div class="val">{{ $quote->quantity }}</div>
      </div>
    </div>
    <div class="row">
      <div class="cell">
        <div class="lbl">Urgency</div>
        <div class="val">
          @php
            $labels = ['asap'=>'ASAP (1–3 days)','week'=>'Within a week','month'=>'Within a month','flexible'=>'Flexible'];
          @endphp
          {{ $labels[$quote->urgency] ?? 'Not specified' }}
        </div>
      </div>
      <div class="cell">
        <div class="lbl">Submitted</div>
        <div class="val">{{ $quote->created_at->format('M d, Y — g:i A') }}</div>
      </div>
    </div>
    @if($quote->description)
    <div class="cell" style="margin-bottom:24px;">
      <div class="lbl" style="margin-bottom:6px;">Description / Notes</div>
      <div class="notes-box">{{ $quote->description }}</div>
    </div>
    @endif
    <div style="text-align:center; margin-top:16px;">
      <a href="mailto:{{ $quote->email }}" class="reply-btn">Reply to Customer</a>
    </div>
  </div>
  <div class="footer">
    <p>Hardware Box &nbsp;·&nbsp; <a href="https://www.thehardwarebox.com">thehardwarebox.com</a></p>
  </div>
</div>
</body>
</html>
