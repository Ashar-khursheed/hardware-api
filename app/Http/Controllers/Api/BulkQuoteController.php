<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkQuoteRequest;
use App\Mail\BulkQuoteAdminMail;
use App\Mail\BulkQuoteCustomerMail;
use App\Models\BulkQuote;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BulkQuoteController extends Controller
{
    public function store(BulkQuoteRequest $request)
    {
        try {
            // 1. Save to database
            $quote = BulkQuote::create($request->validated());

            // 2. Send emails (fail gracefully — don't block the response)
            try {
                $adminEmail = env('ADMIN_EMAIL', 'support@thehardwarebox.com');
                Log::info('Attempting to send bulk quote emails for quote ID: ' . $quote->id);

                // 1. Customer confirmation (Send first to test)
                Mail::to($quote->email)->send(new BulkQuoteCustomerMail($quote));
                Log::info('Customer email sent to: ' . $quote->email);

                // 2. Admin notification
                Mail::to($adminEmail)->send(new BulkQuoteAdminMail($quote));
                Log::info('Admin email sent to: ' . $adminEmail);
            } catch (\Exception $mailException) {
                Log::error('BulkQuote mail error [ID: '.$quote->id.']: ' . $mailException->getMessage());
                Log::error($mailException->getTraceAsString());
            }

            return response()->json([
                'status'  => true,
                'message' => 'Quote request submitted successfully.',
                'data'    => ['id' => $quote->id],
            ], 200);
        } catch (\Exception $e) {
            Log::error('BulkQuote store error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
}
