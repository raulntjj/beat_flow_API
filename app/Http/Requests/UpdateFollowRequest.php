<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'follower_id' => 'nullable|exists:users,id',
            'followed_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array {
        return [
            'follower_id.exists' => 'The selected follower does not exist.',
            'followed_id.exists' => 'The selected followed user does not exist.',
        ];
    }
}
