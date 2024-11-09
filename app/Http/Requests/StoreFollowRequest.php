<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFollowRequest extends FormRequest{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'follower_id' => 'required|exists:users,id',
            'followed_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array {
        return [
            'follower_id.required' => 'The follower_id field is required.',
            'follower_id.exists' => 'The selected follower does not exist.',
            'followed_id.required' => 'The followed_id field is required.',
            'followed_id.exists' => 'The selected followed user does not exist.',
        ];
    }
}
