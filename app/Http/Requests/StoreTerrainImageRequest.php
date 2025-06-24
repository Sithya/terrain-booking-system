<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTerrainImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->terrain->owner_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Image is required.',
            'image.image' => 'File must be a valid image.',
            'image.max' => 'Image size cannot exceed 2MB.',
        ];
    }
}

