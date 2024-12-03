<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'visibility' => 'required|in:private,public,followers',
            'media_type' => 'nullable|in:audio,image|required_with:media_path',
            'media_path' => [
                'nullable',
                'required_with:media_type',
                function ($attribute, $value, $fail) {
                    $mediaType = request('media_type');
                    if ($mediaType === 'audio' && !in_array($value->getClientOriginalExtension(), ['mp3', 'wav', 'aac'])) {
                        $fail('The media path must be a valid audio file (mp3, wav, aac).');
                    } elseif ($mediaType === 'image' && !in_array($value->getClientOriginalExtension(), ['jpeg', 'jpg', 'png'])) {
                        $fail('The media path must be a valid image file (jpeg, jpg, png, gif).');
                    }
                },
            ],
        ];
    }

    public function messages(): array {
        return [
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
            'visibility.required' => 'The visibility field is required.',
            'visibility.in' => 'The visibility must be one of: private, public, followers.',
            'media_type.required_with' => 'The media type is required when media path is provided.',
            'media_path.required_with' => 'The media path is required when media type is provided.',
            'media_type.in' => 'The selected media type must be either audio or image.',
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
