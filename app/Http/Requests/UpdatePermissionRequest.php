<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePermissionRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'nullable|string|unique:permissions,name|max:100',
        ];
    }

    public function messages(): array {
        return [
            'name.string' => 'The name must be a string.',
            'name.unique' => 'The permission name must be unique.',
            'name.max' => 'The name may not be greater than 100 characters.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $response = [
            'status' => 'failed',
            'response' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
