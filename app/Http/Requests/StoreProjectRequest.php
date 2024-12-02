<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProjectRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'owner_id' => 'required|exists:users,id',
            'name' => 'required',
            'content' => 'nullable',
            'cover_path' => 'nullable',
            'media_type' => 'nullable',
            'media_path' => 'nullable',
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
