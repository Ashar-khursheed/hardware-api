<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>New Bulk Quote Request</title>
<style>
  /* ── Base ── */
  body { margin:0; padding:0; background:#f0f2f5; font-family:'Segoe UI',Arial,sans-serif; -webkit-text-size-adjust:100%; }
  table { border-collapse:collapse; mso-table-lspace:0; mso-table-rspace:0; }
  img { border:0; display:block; }

  /* ── Layout ── */
  .email-wrapper { width:100%; background:#f0f2f5; padding:20px 0; }
  .wrap { max-width:640px; margin:0 auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.09); }

  /* ── Header ── */
  .header { background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%); padding:32px 28px; text-align:center; }
  .header h1 { color:#ffffff; font-size:20px; font-weight:800; margin:0 0 10px; line-height:1.3; }
  .badge { display:inline-block; background:#ff6600; color:#fff; font-size:11px; font-weight:700; letter-spacing:1px; padding:5px 16px; border-radius:20px; text-transform:uppercase; }

  /* ── Body ── */
  .body { padding:28px 28px; }
  .section-title { font-size:12px; font-weight:800; color:#ff6600; letter-spacing:1px; text-transform:uppercase; margin:0 0 14px; padding-bottom:8px; border-bottom:2px solid #fff0e6; }

  /* ── Field rows — desktop: side by side ── */
  .row { display:flex; gap:14px; margin-bottom:12px; }
  .cell { flex:1; min-width:0; }
  .cell .lbl { font-size:10px; font-weight:700; color:#999; letter-spacing:.5px; text-transform:uppercase; margin-bottom:4px; }
  .cell .val { font-size:13px; font-weight:600; color:#1a1a2e; background:#f7f8fc; border-left:3px solid #ff6600; border-radius:0 6px 6px 0; padding:8px 12px; word-break:break-word; }

  /* ── Notes ── */
  .notes-box { background:#f7f8fc; border-left:3px solid #ff6600; border-radius:0 8px 8px 0; padding:12px 14px; font-size:13px; color:#444; line-height:1.65; word-break:break-word; }

  .divider { border:none; border-top:1px solid #f0f0f0; margin:20px 0; }

  /* ── CTA button ── */
  .btn-wrap { text-align:center; margin-top:18px; }
  .reply-btn { display:inline-block; background:#ff6600; color:#fff; text-decoration:none; font-weight:700; font-size:14px; padding:13px 32px; border-radius:8px; }

  /* ── Footer ── */
  .footer { background:#f7f8fc; padding:18px 28px; text-align:center; font-size:12px; color:#aaa; }
  .footer a { color:#ff6600; text-decoration:none; }

  /* ══ MOBILE — stack everything ══ */
  @media only screen and (max-width: 600px) {
    .email-wrapper { padding:0 !important; }
    .wrap { border-radius:0 !important; box-shadow:none !important; }

    .header { padding:24px 16px !important; }
    .header h1 { font-size:17px !important; }

    .body { padding:20px 16px !important; }

    /* Stack left-right fields into top-bottom */
    .row { flex-direction:column !important; gap:10px !important; margin-bottom:10px !important; }
    .cell { width:100% !important; }

    /* Full-width CTA */
    .reply-btn {
      display:block !important;
      width:100% !important;
      box-sizing:border-box !important;
      text-align:center !important;
      padding:14px 16px !important;
      border-radius:8px !important;
    }

    .footer { padding:16px !important; }
  }
</style>
</head>
<body>
<div class="email-wrapper">
<div class="wrap">

  {{-- Header --}}
  <div class="header">
    <h1>🔔 New Bulk Quote Request</h1>
    <span class="badge">Action Required</span>
  </div>

  {{-- Body --}}
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
            $labels = [
                'asap'     => 'ASAP (1–3 days)',
                'week'     => 'Within a week',
                'month'    => 'Within a month',
                'flexible' => 'Flexible',
                'future'   => 'Future Project'
            ];
          @endphp
          {{ $labels[$quote->urgency] ?? 'Not specified' }}
        </div>
      </div>
      <div class="cell">
        <div class="lbl">Submitted</div>
        <div class="val">{{ $quote->created_at->format('M d, Y g:i A') }}</div>
      </div>
    </div>

    @if($quote->description)
    <div style="margin-bottom:20px;">
      <div class="lbl" style="font-size:10px;font-weight:700;color:#999;letter-spacing:.5px;text-transform:uppercase;margin-bottom:6px;">Description / Notes</div>
      <div class="notes-box">{{ $quote->description }}</div>
    </div>
    @endif

    <div class="btn-wrap">
      <a href="mailto:{{ $quote->email }}" class="reply-btn">Reply to Customer</a>
    </div>

  </div>

  {{-- Footer --}}
  <div class="footer">
    <p>Hardware Box &nbsp;·&nbsp; <a href="https://www.thehardwarebox.com">thehardwarebox.com</a></p>
  </div>

</div>
</div>
</body>
</html>
