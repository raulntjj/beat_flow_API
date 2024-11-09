<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'nullable|string|unique:roles,name|max:100',
        ];
    }

    public function messages(): array {
        return [
            'name.string' => 'The name must be a string.',
            'name.unique' => 'The role name must be unique.',
            'name.max' => 'The name may not be greater than 100 characters.',
        ];
    }
}
