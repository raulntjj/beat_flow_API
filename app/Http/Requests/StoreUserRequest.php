<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'user' => 'required|string|max:255|unique:users,user',
            'password' => 'required|string|min:8|confirmed',
            // 'profile_photo_path' => 'required',
            'profile_photo_path' => 'nullable|mimes:jpg,jpeg,png',
            'bio' => 'nullable|string|max:500',
            'is_private' => 'required|boolean',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'last_name.required' => 'The last name field is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'user.required' => 'The user field is required.',
            'user.unique' => 'The username has already been taken.',
            'user.max' => 'The username may not be greater than 255 characters.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            // 'profile_photo_path.required' => 'The profile photo field is required.',
            'bio.string' => 'The bio must be a string.',
            'bio.max' => 'The bio may not be greater than 500 characters.',
            'is_private.required' => 'The is_private field is required.',
            'is_private.boolean' => 'The is_private field must be true or false.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $response = [
            'status' => 'failed',
            'response' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 200));
    }
}
