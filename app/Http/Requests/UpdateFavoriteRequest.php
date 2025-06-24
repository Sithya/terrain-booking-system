<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->favorite->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            // Favorites typically don't have updatable fields
            // This could be used for future enhancements like favorite categories
        ];
    }
}
