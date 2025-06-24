<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (
            $this->booking->renter_id === auth()->id() ||
            $this->booking->terrain->owner_id === auth()->id()
        );
    }

    public function rules(): array
    {
        return [
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'status' => 'sometimes|required|in:pending,approved,rejected,cancelled,completed',
        ];
    }
}

