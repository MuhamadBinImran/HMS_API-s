<?php

namespace App\Services;

use App\Models\Prescription;

class PrescriptionService
{
    public function store(array $data): Prescription
    {
        return Prescription::create($data);
    }

    public function getByAppointment($appointmentId)
    {
        return Prescription::where('appointment_id', $appointmentId)->first();
    }
}
