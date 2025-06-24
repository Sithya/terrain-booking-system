<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTerrainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'location' => 'required|string|max:255',
            'area_size' => 'required|numeric|min:0.01',
            'price_per_day' => 'required|numeric|min:0.01',
            'available_from' => 'nullable|date|after_or_equal:today',
            'available_to' => 'nullable|date|after:available_from',
            'is_available' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Terrain title is required.',
            'location.required' => 'Location is required.',
            'area_size.required' => 'Area size is required.',
            'area_size.min' => 'Area size must be greater than 0.',
            'price_per_day.required' => 'Price per day is required.',
            'price_per_day.min' => 'Price per day must be greater than 0.',
            'available_to.after' => 'End date must be after start date.',
            'main_image.image' => 'Main image must be a valid image file.',
            'main_image.max' => 'Main image size cannot exceed 2MB.',
        ];
    }
}
