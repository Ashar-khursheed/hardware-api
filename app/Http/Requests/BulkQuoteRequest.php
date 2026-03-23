<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public endpoint
    }

    public function rules(): array
    {
        return [
            'full_name'   => 'required|string|max:255',
            'org_name'    => 'nullable|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:30',
            'part_number' => 'required|string|max:255',
            'quantity'    => 'required|string|max:50',
            'urgency'     => 'nullable',
            'description' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required'   => 'Full name is required.',
            'email.required'       => 'Email address is required.',
            'email.email'          => 'Please provide a valid email address.',
            'phone.required'       => 'Phone number is required.',
            'part_number.required' => 'Part number or product name is required.',
            'quantity.required'    => 'Quantity is required.',
        ];
    }
}
