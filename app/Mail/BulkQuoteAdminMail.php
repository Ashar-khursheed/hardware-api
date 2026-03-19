<?php

namespace App\Mail;

use App\Models\BulkQuote;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkQuoteAdminMail extends Mailable
{
    use SerializesModels;

    public BulkQuote $quote;

    public function __construct(BulkQuote $quote)
    {
        $this->quote = $quote;
    }

    public function build()
    {
        return $this
            ->subject("🔔 New Bulk Quote — {$this->quote->part_number} × {$this->quote->quantity} from {$this->quote->full_name}")
            ->view('emails.bulk-quote-admin');
    }
}
