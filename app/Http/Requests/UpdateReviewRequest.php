<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->review->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'rating' => 'sometimes|required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
