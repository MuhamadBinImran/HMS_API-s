<?php

namespace App\Services;

use App\Models\Appointment;
use App\DTOs\AppointmentDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentApprovedMail;
use App\Mail\AppointmentRejectedMail;
use Exception;

class AppointmentService
{
    /**
     * Store a new appointment
     */
    public function store(AppointmentDTO $dto): Appointment
    {
        try {
            // Prevent double booking
            $exists = Appointment::where('doctor_id', $dto->doctor_id)
                ->where('appointment_time', $dto->appointment_time)
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($exists) {
                throw new Exception('Doctor is already booked at this time.');
            }

            return Appointment::create([
                'patient_id'       => $dto->patient_id,
                'doctor_id'        => $dto->doctor_id,
                'appointment_time' => $dto->appointment_time,
                'notes'            => $dto->notes,
                'status'           => 'pending',
            ]);

        } catch (Exception $e) {
            Log::error('[AppointmentService][store] ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Approve an appointment and send email using queue
     */
    public function approve(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'approved']);

        try {
            $email = optional($appointment->patient->user)->email;
            if ($email) {
                Mail::to($email)->queue(new AppointmentApprovedMail($appointment));
            } else {
                Log::warning("[AppointmentService][approve] Patient email not found for appointment ID {$appointment->id}");
            }
        } catch (Exception $e) {
            Log::warning("[AppointmentService][approve][mail] " . $e->getMessage());
        }

        return $appointment;
    }

    /**
     * Reject an appointment and send email using queue
     */
    public function reject(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => 'rejected']);

        try {
            $email = optional($appointment->patient->user)->email;
            if ($email) {
                Mail::to($email)->queue(new AppointmentRejectedMail($appointment));
            } else {
                Log::warning("[AppointmentService][reject] Patient email not found for appointment ID {$appointment->id}");
            }
        } catch (Exception $e) {
            Log::warning("[AppointmentService][reject][mail] " . $e->getMessage());
        }

        return $appointment;
    }

    /**
     * Delete an appointment
     */
    public function delete(Appointment $appointment): void
    {
        $appointment->delete();
    }
}
