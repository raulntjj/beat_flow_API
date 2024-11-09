<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedRequest extends FormRequest {
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'nullable|exists:users,id',
            'shared_post_id' => 'nullable|exists:shared_posts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.required' => 'The post_id field is required.',
            'post_id.exists' => 'The selected post does not exist.',
            'user_id.exists' => 'The selected user does not exist.',
            'shared_post_id.exists' => 'The selected shared post does not exist.',
        ];
    }
}
