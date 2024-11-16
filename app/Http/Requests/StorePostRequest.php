<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'visibility' => 'required|in:private,public,followers',
            'media_type' => 'required|in:audio,image',
            'media_path' => 'required|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
            'visibility.required' => 'The visibility field is required.',
            'visibility.in' => 'The visibility must be one of: private, public, followers.',
            'media_type.required' => 'The media_type field is required.',
            'media_type.in' => 'The media_type must be one of: audio, image.',
            'media_path.required' => 'The media_path field is required.',
            'media_path.string' => 'The media_path must be a string.',
            'media_path.max' => 'The media_path may not be greater than 255 characters.',
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
