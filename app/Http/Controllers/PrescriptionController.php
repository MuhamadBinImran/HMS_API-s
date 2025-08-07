<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Services\PrescriptionService;
use App\Http\Requests\StorePrescriptionRequest;
class PrescriptionController extends Controller
{
    protected PrescriptionService $prescriptionService;

    public function __construct(PrescriptionService $prescriptionService)
    {
        $this->prescriptionService = $prescriptionService;
    }



    public function store(StorePrescriptionRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user();
        $doctorProfile = $user->doctorProfile;

        if (!$doctorProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor profile not found.'
            ], 403);
        }

        // Check if the appointment belongs to this doctor
        $appointment = \App\Models\Appointment::where('id', $data['appointment_id'])
            ->where('doctor_id', $doctorProfile->id)
            ->where('status', 'approved') // optionally ensure appointment is approved
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or invalid appointment.'
            ], 403);
        }

        // Prevent duplicate prescriptions for the same appointment
        $existing = \App\Models\Prescription::where('appointment_id', $appointment->id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'A prescription already exists for this appointment.'
            ], 409);
        }

        // Now safely create prescription
        $prescription = $this->prescriptionService->store($data);

        return response()->json([
            'success' => true,
            'message' => 'Prescription created successfully.',
            'data'    => $prescription
        ], 201);
    }


    public function showByAppointment($appointmentId)
    {
        $user = auth()->user();

        // Get the patient profile ID
        $patientProfile = $user->patientProfile;

        if (!$patientProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Patient profile not found.'
            ], 404);
        }

        // Verify this appointment belongs to the authenticated patient
        $appointment = \App\Models\Appointment::where('id', $appointmentId)
            ->where('patient_id', $patientProfile->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found or unauthorized.'
            ], 403);
        }

        // Get prescription
        $prescription = \App\Models\Prescription::where('appointment_id', $appointmentId)->first();

        if (!$prescription) {
            return response()->json([
                'success' => false,
                'message' => 'No prescription found for this appointment.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $prescription
        ]);
    }

    public function doctorPrescriptions()
    {
        $user = auth()->user();
        $doctorProfile = $user->doctorProfile;

        if (!$doctorProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor profile not found.'
            ], 403);
        }

        $prescriptions = \App\Models\Prescription::with(['appointment.patient.user'])
            ->whereHas('appointment', function ($query) use ($doctorProfile) {
                $query->where('doctor_id', $doctorProfile->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }



}
