<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeUpdateRequest extends FormRequest
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
            "image" => ['mimes:jpeg,jpg,png', File::image()
                ->max(1024)],
            'phone' => ["min:12", 'max:13'],
            'division' => ['exists:divisions,uuid'],
        ];
    }

    public function messages()
    {
        return [
            'image.mimes' => 'File gambar hanya mendukung format jpeg, jpg, atau png',
            'image.max' => 'Ukuran file gambar maksimal 1024 KB / 1 MB',
            'phone.min' => 'jumlah karakter minimal 12 karakter',
            'phone.max' => 'jumlah karakter maksimal 13 karakter',
            'division.exists' => 'id divisi tidak dapat ditemukan',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors'    => $validator->errors()
        ], 422));
    }
}
