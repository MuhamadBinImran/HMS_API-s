<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // Ensure user is authenticated
    }

    public function rules(): array
    {
        return [
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_time' => 'required|date|after_or_equal:now',
            'notes'            => 'nullable|string',
            'patient_id'       => 'sometimes|exists:patients,id', // Allow patient_id only if sent
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        $user = auth()->user();

        if ($user && $user instanceof \App\Models\User && $user->hasRole('patient')) {
            $data['patient_id'] = optional($user->patientProfile)->id;
        }

        return $data;
    }

}
