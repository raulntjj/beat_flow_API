<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateNotificationRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'post_id' => 'nullable|exists:posts,id',
            'type' => 'nullable|in:follow,like,comment',
            'is_read' => 'nullable|boolean',
            'content' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'post_id.exists' => 'The selected post does not exist.',
            'type.in' => 'The type must be one of: follow, like, comment.',
            'is_read.boolean' => 'The is_read field must be true or false.',
            'content.string' => 'The content must be a string.',
            'content.max' => 'The content may not be greater than 255 characters.',
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
