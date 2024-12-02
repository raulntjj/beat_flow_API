<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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

    protected function failedValidation(Validator $validator) {
        $response = [
            'status' => 'failed',
            'response' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 200));
    }
}
