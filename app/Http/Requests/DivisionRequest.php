<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DivisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:divisions'
        ];
    }

    /**
     * Customize validation message
     */

    public function messages(): array
    {
        return [
            'name.required'   => 'nama divisi tidak boleh kosong',
            'name.unique:divisions' => 'nama divisi sudah tersedia'
        ];
    }

    /**
     * Customize validation message
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors'    => $validator->errors()
        ], 422));
    }
}
