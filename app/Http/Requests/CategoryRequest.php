<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
