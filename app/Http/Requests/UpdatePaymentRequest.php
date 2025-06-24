<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (
            $this->payment->booking->renter_id === auth()->id() ||
            $this->payment->booking->terrain->owner_id === auth()->id()
        );
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|required|in:paid,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
        ];
    }
}
