<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFeedRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'post_id' => 'nullable|exists:posts,id|required_without:shared_post_id',
            'shared_post_id' => 'nullable|exists:shared_posts,id|required_without:post_id',
        ];
    }

    public function messages(): array {
        return [
            'post_id.required_without' => 'The post ID is required when shared post ID is not provided.',
            'shared_post_id.required_without' => 'The shared post ID is required when post ID is not provided.',
            'post_id.exists' => 'The selected post ID is invalid.',
            'shared_post_id.exists' => 'The selected shared post ID is invalid.',
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
