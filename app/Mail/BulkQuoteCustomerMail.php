<?php

namespace App\Mail;

use App\Models\BulkQuote;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkQuoteCustomerMail extends Mailable
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
            ->subject('✅ Your Quote Request Was Received — Hardware Box')
            ->view('emails.bulk-quote-customer');
    }
}
