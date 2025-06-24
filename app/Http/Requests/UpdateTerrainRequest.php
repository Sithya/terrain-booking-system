<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTerrainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'location' => 'sometimes|required|string|max:255',
            'area_size' => 'sometimes|required|numeric|min:0.01',
            'price_per_day' => 'sometimes|required|numeric|min:0.01',
            'available_from' => 'nullable|date',
            'available_to' => 'nullable|date|after:available_from',
            'is_available' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
