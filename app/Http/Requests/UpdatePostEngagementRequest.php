<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostEngagementRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'post_id' => 'nullable|exists:posts,id',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|in:like,share',
        ];
    }

    public function messages(): array {
        return [
            'post_id.exists' => 'The selected post does not exist.',
            'user_id.exists' => 'The selected user does not exist.',
            'type.in' => 'The type must be one of: like, share.',
        ];
    }
}
