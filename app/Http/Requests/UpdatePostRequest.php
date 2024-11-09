<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'user_id' => 'nullable|exists:users,id',
            'content' => 'nullable|string',
            'visibility' => 'nullable|in:private,public,followers',
            'media_type' => 'nullable|in:audio,image',
            'media_path' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'user_id.exists' => 'The selected user does not exist.',
            'content.string' => 'The content must be a string.',
            'visibility.in' => 'The visibility must be one of: private, public, followers.',
            'media_type.in' => 'The media_type must be one of: audio, image.',
            'media_path.string' => 'The media_path must be a string.',
            'media_path.max' => 'The media_path may not be greater than 255 characters.',
        ];
    }
}
