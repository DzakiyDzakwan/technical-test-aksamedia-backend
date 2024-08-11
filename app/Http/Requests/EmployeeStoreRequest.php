<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeStoreRequest extends FormRequest
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
            "image" => ['required', 'mimes:jpeg,jpg,png', File::image()
                ->max(1024)],
            'name' => 'required',
            'phone' => ['required', "min:12", 'max:13'],
            'division' => ['required', 'exists:divisions,uuid'],
            'position' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'foto pegawai tidak boleh kosong',
            'image.mimes' => 'File gambar hanya mendukung format jpeg, jpg, atau png',
            'image.max' => 'Ukuran file gambar maksimal 1024 KB / 1 MB',
            'image.dimensions' => 'Dimensi gambar tidak sesuai (maksimal 1000x500)',
            'name.required' => 'nama pegawai tidak boleh kosong',
            'phone.required' => 'nomor telepon pegawai tidak boleh kosong',
            'phone.min' => 'jumlah karakter minimal 12 karakter',
            'phone.max' => 'jumlah karakter maksimal 13 karakter',
            'division.required' => 'id divisi tidak boleh kosong',
            'division.exists' => 'id divisi tidak dapat ditemukan',
            'position' => 'posisi tidak boleh kosong'
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
