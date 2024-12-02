<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNotificationRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:follow,like,comment,share',
            'is_read' => 'required|boolean',
            'content' => 'nullable|required_without:notifier_name|string|max:255',
            'notifier_name' => 'nullable|required_without:content|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The type must be one of: follow, like, comment.',
            'is_read.required' => 'The is_read field is required.',
            'is_read.boolean' => 'The is_read field must be true or false.',
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
