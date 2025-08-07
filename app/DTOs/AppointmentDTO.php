<?php

namespace App\DTOs;

class AppointmentDTO
{
    /** @var int|null */
    public ?int $patient_id;

    /** @var int */
    public int $doctor_id;

    /** @var string */
    public string $appointment_time;

    /** @var string|null */
    public ?string $notes;

    public function __construct(array $data)
    {
        // Allow patient_id to be optional in case admin is assigning it differently
        $this->patient_id = isset($data['patient_id']) ? (int) $data['patient_id'] : null;
        $this->doctor_id = (int) $data['doctor_id'];
        $this->appointment_time = $data['appointment_time'];
        $this->notes = $data['notes'] ?? null;
    }
}
