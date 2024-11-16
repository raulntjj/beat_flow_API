<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFeedRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_id' => 'nullable|exists:posts,id',
            'shared_post_id' => 'nullable|exists:shared_posts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.exists' => 'The selected post does not exist.',
            'shared_post_id.exists' => 'The selected shared post does not exist.',
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
