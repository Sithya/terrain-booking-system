<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTerrainImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->terrainImage->terrain->owner_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
