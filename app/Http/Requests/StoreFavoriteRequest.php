<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'terrain_id' => 'required|exists:terrains,id',
        ];
    }

    public function messages(): array
    {
        return [
            'terrain_id.required' => 'Terrain selection is required.',
            'terrain_id.exists' => 'Selected terrain does not exist.',
        ];
    }
}
