<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreGenreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string|unique:genres,slug|max:100',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 100 characters.',
            'slug.required' => 'The slug field is required.',
            'slug.string' => 'The slug must be a string.',
            'slug.unique' => 'The slug must be unique.',
            'slug.max' => 'The slug may not be greater than 100 characters.',
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
