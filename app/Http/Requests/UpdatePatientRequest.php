<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Patient;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $patientId = $this->route('id');
        $patient = Patient::find($patientId);

        return [
            'name'     => 'sometimes|required|string|max:255',
            'email'    => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($patient?->user_id),
            ],
            'gender'   => [
                'sometimes',
                'required',
                Rule::in(['male', 'female', 'other']),
            ],
            'dob'      => 'sometimes|required|date',
            'address'  => 'sometimes|nullable|string|max:255',
            'phone'    => 'sometimes|nullable|string|max:15',
            'password' => 'sometimes|required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The email is already in use.',
            'email.email' => 'Enter a valid email address.',
            'name.string' => 'Name must be a valid string.',
            'gender.in' => 'Gender must be male, female, or other.',
            'dob.date' => 'Date of birth must be a valid date.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('gender')) {
            $this->merge([
                'gender' => strtolower($this->input('gender')),
            ]);
        }

        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower($this->input('email')),
            ]);
        }
    }
}
