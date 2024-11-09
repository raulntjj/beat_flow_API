<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|unique:permissions,name|max:100',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.unique' => 'The permission name must be unique.',
            'name.max' => 'The name may not be greater than 100 characters.',
        ];
    }
}
