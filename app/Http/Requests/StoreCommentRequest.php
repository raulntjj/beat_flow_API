<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCommentRequest extends FormRequest {
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'post_id.required' => 'The post_id field is required.',
            'post_id.exists' => 'The selected post does not exist.',
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
            'content.max' => 'The content may not be greater than 255 characters.',
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
