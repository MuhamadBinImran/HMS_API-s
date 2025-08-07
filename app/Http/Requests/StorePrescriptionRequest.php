<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ✅ Assuming role check is handled by middleware
    }

    public function rules(): array
    {
        return [
            'appointment_id' => 'required|exists:appointments,id',
            'medicines'      => 'required|string',
            'instructions'   => 'nullable|string',
        ];
    }
}
