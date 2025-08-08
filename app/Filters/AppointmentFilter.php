<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class AppointmentFilter extends BaseFilter
{
    public function apply(Builder $query): Builder
    {
        // Filter by status
        if ($status = $this->request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by doctor_id
        if ($doctorId = $this->request->get('doctor_id')) {
            $query->where('doctor_id', $doctorId);
        }

        // Filter by patient_id
        if ($patientId = $this->request->get('patient_id')) {
            $query->where('patient_id', $patientId);
        }

        // Filter by date (appointment_time)
        if ($date = $this->request->get('date')) {
            $query->whereDate('appointment_time', $date);
        }

        return $query;
    }
}
