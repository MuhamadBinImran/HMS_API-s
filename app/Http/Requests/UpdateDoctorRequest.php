<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'sometimes|required|string|max:255',
            'email'          => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('id')),
            ],
            'specialization' => 'sometimes|required|string|max:255',
            'phone'          => 'sometimes|required|string|max:20',
            'address'        => 'sometimes|required|string|max:255',
            'password'       => 'sometimes|required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'         => 'This email is already taken.',
            'email.email'          => 'Provide a valid email address.',
            'name.string'          => 'Name must be a valid string.',
            'name.max'             => 'Name must not exceed 255 characters.',
            'specialization.string'=> 'Specialization must be a valid string.',
            'specialization.max'   => 'Specialization must not exceed 255 characters.',
            'phone.string'         => 'Phone must be a valid string.',
            'phone.max'            => 'Phone number must not exceed 20 characters.',
            'address.string'       => 'Address must be a valid string.',
            'address.max'          => 'Address must not exceed 255 characters.',
            'password.required'    => 'Password is required.',
            'password.string'      => 'Password must be a valid string.',
            'password.min'         => 'Password must be at least 6 characters long.',
        ];
    }
}
