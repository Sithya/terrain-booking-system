<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->booking->renter_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer,cash',
            'amount_paid' => 'required|numeric|min:0.01',
            'transaction_id' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'booking_id.required' => 'Booking ID is required.',
            'booking_id.exists' => 'Selected booking does not exist.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
            'amount_paid.required' => 'Payment amount is required.',
            'amount_paid.min' => 'Payment amount must be greater than 0.',
        ];
    }
}
