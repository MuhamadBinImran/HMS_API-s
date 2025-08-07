<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Services\DoctorService;
use App\Models\Doctor;
use App\DTOs\DoctorDTO;
use App\DTOs\UpdateDoctorDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    /**
     * Display a listing of doctors.
     */
    public function index(): AnonymousResourceCollection
    {
        $doctors = $this->doctorService->getAll();
        return DoctorResource::collection($doctors);
    }

    /**
     * Store a newly created doctor.
     */
    public function store(DoctorRequest $request): DoctorResource
    {
        $dto = DoctorDTO::fromRequest($request);
        $doctor = $this->doctorService->create($dto);

        // Ensure the role is assigned if not done inside the service
        if (!$doctor->user->hasRole('doctor')) {
            $doctor->user->assignRole('doctor');
        }

        return new DoctorResource($doctor);
    }

    /**
     * Display the specified doctor.
     */
    public function show(int $id): DoctorResource
    {
        $doctor = $this->doctorService->getById($id);
        return new DoctorResource($doctor);
    }

    /**
     * Update the specified doctor.
     */
    public function update(UpdateDoctorRequest $request, int $id): DoctorResource
    {
        $dto = UpdateDoctorDTO::fromRequest($request);
        $doctor = $this->doctorService->update($id, $dto);
        return new DoctorResource($doctor);
    }

    /**
     * Remove the specified doctor.
     */
    public function destroy(int $id): JsonResponse
    {
        $doctor = Doctor::findOrFail($id);
        $this->doctorService->delete($doctor);

        return response()->json([
            'message' => 'Doctor deleted successfully.',
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        $doctorProfile = $user->doctorProfile;

        if (!$doctorProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor profile not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'name'           => $user->name,
                'email'          => $user->email,
                'specialization' => $doctorProfile->specialization,
                'phone'          => $doctorProfile->phone,
                'availability'   => $doctorProfile->availability_schedule,
            ]
        ]);
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $doctorProfile = $user->doctorProfile;

        if (!$doctorProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor profile not found.'
            ], 404);
        }

        $validated = $request->validate([
            'phone'               => 'sometimes|required|string|max:20',
            'specialization'      => 'sometimes|required|string|max:100'
        ]);

        // Update only provided fields
        $doctorProfile->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Doctor profile updated successfully.',
            'data'    => $doctorProfile
        ]);
    }


}
