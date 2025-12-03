<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
        $rules = [
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:L,P'],
            'birth_date' => ['nullable', 'date', 'before:today'],
        ];

        // Add role-specific validation
        if ($this->role_id) {
            $role = \App\Models\Role::find($this->role_id);
            
            if ($role && $role->name === 'mahasiswa') {
                $rules['nim'] = ['required', 'string', 'unique:users,nim'];
            } elseif ($role && $role->name === 'dosen') {
                $rules['nip'] = ['required', 'string', 'unique:users,nip'];
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role_id.required' => 'Role harus dipilih.',
            'role_id.exists' => 'Role tidak valid.',
            'nim.required' => 'NIM wajib diisi untuk mahasiswa.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nip.required' => 'NIP wajib diisi untuk dosen.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'gender.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
        ];
    }
}
