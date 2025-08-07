<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Normalize gender to lowercase before validation
    protected function prepareForValidation(): void
    {
        if ($this->has('gender') && is_string($this->gender)) {
            $this->merge([
                'gender' => strtolower($this->gender),
            ]);
        }
    }



    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'gender'   => 'nullable|in:male,female,other',
            'dob'      => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Name is required.',
            'email.required'    => 'Email is required.',
            'email.email'       => 'Please enter a valid email address.',
            'email.unique'      => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 6 characters.',
            'gender.in'         => 'Gender must be one of: male, female, or other.',
            'dob.date'          => 'Date of birth must be a valid date.',
        ];
    }
}
