<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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

    protected function failedValidation(Validator $validator) {
        $response = [
            'status' => 'failed',
            'response' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
