<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            // 'email' => 'nullable|string|email|max:255|unique:users,email,' . $this->user,
            // 'user' => 'nullable|string|max:255|unique:users,user,' . $this->user,
            // 'password' => 'nullable|string|min:8|confirmed',
            'profile_photo_path' => 'nullable',
            'bio' => 'nullable|string|max:500',
            'is_private' => 'nullable|boolean',
        ];
    }

    public function messages(): array {
        return [
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',
            // 'email.email' => 'The email must be a valid email address.',
            // 'email.unique' => 'The email has already been taken.',
            // 'email.max' => 'The email may not be greater than 255 characters.',
            // 'user.string' => 'The user must be a string.',
            // 'user.unique' => 'The username has already been taken.',
            // 'user.max' => 'The username may not be greater than 255 characters.',
            // 'password.min' => 'The password must be at least 8 characters.',
            // 'password.confirmed' => 'The password confirmation does not match.',
            // 'profile_photo_path.max' => 'The profile photo path may not be greater than 255 characters.',
            'bio.string' => 'The bio must be a string.',
            'bio.max' => 'The bio may not be greater than 500 characters.',
            'is_private.boolean' => 'The is_private field must be true or false.',
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
