<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\DTOs\PatientDTO;
use App\DTOs\UpdatePatientDTO;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Services\PatientService;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
class PatientController extends Controller
{
    protected PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    /**
     * Display a listing of all patients.
     */
    public function index(): AnonymousResourceCollection
    {
        $patients = $this->patientService->getAll();
        return PatientResource::collection($patients);
    }

    /**
     * Store a newly created patient with user account.
     */
    public function store(PatientRequest $request): PatientResource
    {
        $dto = PatientDTO::fromRequest($request);

        $patient = $this->patientService->create($dto);

        return new PatientResource($patient);
    }
    /**
     * Display the specified patient.
     */
    public function show(int $id): PatientResource
    {
        $patient = $this->patientService->getById($id);
        return new PatientResource($patient);
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(UpdatePatientRequest $request, int $id): PatientResource
    {
        $dto = UpdatePatientDTO::fromRequest($request);
        $patient = $this->patientService->update($id, $dto);
        return new PatientResource($patient);
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $patient = Patient::with('user')->findOrFail($id);
        $this->patientService->delete($patient);

        return response()->json([
            'message' => 'Patient deleted successfully.'
        ], Response::HTTP_OK);
    }


    public function filteredIndex(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->whereHas('user', function($q) use ($name) {
                $q->where('name', 'like', "%{$name}%");
            });
        }

        if ($request->filled('email')) {
            $email = $request->input('email');
            $query->whereHas('user', function($q) use ($email) {
                $q->where('email', 'like', "%{$email}%");
            });
        }

        if ($request->filled('phone')) {
            $phone = $request->input('phone');
            $query->where('phone', 'like', "%{$phone}%");
        }

        $patients = $query->paginate(5);

        return PatientResource::collection($patients);
    }

}
