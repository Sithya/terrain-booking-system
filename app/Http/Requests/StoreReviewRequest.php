<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'terrain_id' => 'required|exists:terrains,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'terrain_id.required' => 'Terrain selection is required.',
            'terrain_id.exists' => 'Selected terrain does not exist.',
            'rating.required' => 'Rating is required.',
            'rating.between' => 'Rating must be between 1 and 5.',
            'comment.max' => 'Comment cannot exceed 1000 characters.',
        ];
    }
}
