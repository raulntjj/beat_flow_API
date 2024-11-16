<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class UpdateSharedPostRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'post_id' => 'nullable|exists:posts,id',
            'user_id' => 'nullable|exists:users,id',
            'comment' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'post_id.exists' => 'The selected post does not exist.',
            'user_id.exists' => 'The selected user does not exist.',
            'comment.string' => 'The comment must be a string.',
            'comment.max' => 'The comment may not be greater than 255 characters.',
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
