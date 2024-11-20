<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePostRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'user_id' => 'nullable|exists:users,id',
            'content' => 'nullable|string',
            'visibility' => 'nullable|in:private,public,followers',
            'media_type' => 'nullable|in:audio,image,video',
            'media_path' => 'nullable',
        ];
    }

    public function messages(): array {
        return [
            'user_id.exists' => 'The selected user does not exist.',
            'content.string' => 'The content must be a string.',
            'visibility.in' => 'The visibility must be one of: private, public, followers.',
            'media_type.in' => 'The media_type must be one of: audio, image or video.',
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
