<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGenreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'nullable|string|max:100',
            'slug' => 'nullable|string|unique:genres,slug|max:100',
        ];
    }

    public function messages(): array {
        return [
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 100 characters.',
            'slug.string' => 'The slug must be a string.',
            'slug.unique' => 'The slug must be unique.',
            'slug.max' => 'The slug may not be greater than 100 characters.',
        ];
    }
}
