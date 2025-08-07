<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use App\DTOs\AppointmentDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendAppointmentEmailJob;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($user->hasRole('admin')) {
            $appointments = Appointment::with(['patient.user', 'doctor.user'])
                ->latest()
                ->get();
        } elseif ($user->hasRole('patient')) {
            $patientProfile = $user->patientProfile;

            if (!$patientProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'No patient profile found.',
                ], 404);
            }

            $appointments = Appointment::with(['patient.user', 'doctor.user'])
                ->where('patient_id', $patientProfile->id)
                ->latest()
                ->get();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right roles.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => AppointmentResource::collection($appointments),
        ]);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated or invalid user.',
            ], 401);
        }

        $data = $request->validated();

        if ($user->hasRole('patient')) {
            $patientProfile = $user->patientProfile;

            if (!$patientProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'No patient profile associated with this user.',
                ], 422);
            }

            $data['patient_id'] = $patientProfile->id;
        }

        if ($user->hasRole('admin') && empty($data['patient_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'The patient_id field is required for admin users.',
            ], 422);
        }

        try {
            $dto = new AppointmentDTO($data);
            $appointment = $this->appointmentService->store($dto);

            // ✅ Dispatch queued mail job
            dispatch(new SendAppointmentEmailJob($appointment));

            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully.',
                'data' => new AppointmentResource($appointment),
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Error storing appointment: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create appointment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $appointment = Appointment::with(['patient.user', 'doctor.user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new AppointmentResource($appointment),
        ]);
    }

    public function approve($id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);
        $this->appointmentService->approve($appointment);

        return response()->json([
            'success' => true,
            'message' => 'Appointment approved successfully.',
            'data' => new AppointmentResource($appointment),
        ]);
    }

    public function reject($id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);
        $this->appointmentService->reject($appointment);

        return response()->json([
            'success' => true,
            'message' => 'Appointment rejected successfully.',
            'data' => new AppointmentResource($appointment),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);
        $this->appointmentService->delete($appointment);

        return response()->json([
            'success' => true,
            'message' => 'Appointment deleted successfully.',
        ]);
    }
}
