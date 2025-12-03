<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user');

        $rules = [
            'role_id' => ['sometimes', 'exists:roles,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => ['sometimes', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:L,P'],
            'birth_date' => ['nullable', 'date', 'before:today'],
        ];

        // Add role-specific validation
        if ($this->role_id) {
            $role = \App\Models\Role::find($this->role_id);
            
            if ($role && $role->name === 'mahasiswa') {
                $rules['nim'] = ['sometimes', 'string', Rule::unique('users')->ignore($userId)];
            } elseif ($role && $role->name === 'dosen') {
                $rules['nip'] = ['sometimes', 'string', Rule::unique('users')->ignore($userId)];
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
            'role_id.exists' => 'Role tidak valid.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'gender.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
        ];
    }
}
