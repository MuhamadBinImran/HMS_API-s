<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Modify if role-based access is added later
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
            'name'           => 'bail|required|string|max:255',
            'email'          => 'bail|required|email|unique:users,email',
            'password'       => 'required|string|min:6|confirmed',
            'specialization' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'gender'         => 'nullable|in:male,female,other',
            'dob'            => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Name is required.',
            'email.required'        => 'Email is required.',
            'email.email'           => 'Please enter a valid email address.',
            'email.unique'          => 'A user with this email already exists.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 6 characters.',
            'password.confirmed'    => 'Passwords do not match.',
            'specialization.required' => 'Specialization is required.',
            'phone.required'        => 'Phone number is required.',
            'address.required'      => 'Address is required.',
            'gender.in'             => 'Gender must be one of: male, female, or other.',
            'dob.date'              => 'Date of birth must be a valid date.',
        ];
    }
}
