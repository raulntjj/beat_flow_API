<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostEngagementRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:like,share',
        ];
    }

    public function messages(): array {
        return [
            'post_id.required' => 'The post_id field is required.',
            'post_id.exists' => 'The selected post does not exist.',
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The type must be one of: like, share.',
        ];
    }
}
