<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'post_id' => 'required|exists:posts,id',
            'type' => 'required|in:follow,like,comment',
            'is_read' => 'required|boolean',
            'content' => 'required|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'post_id.required' => 'The post_id field is required.',
            'post_id.exists' => 'The selected post does not exist.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The type must be one of: follow, like, comment.',
            'is_read.required' => 'The is_read field is required.',
            'is_read.boolean' => 'The is_read field must be true or false.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
            'content.max' => 'The content may not be greater than 255 characters.',
        ];
    }
}
