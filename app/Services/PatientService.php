<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\User;
use App\DTOs\PatientDTO;
use App\DTOs\UpdatePatientDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class PatientService
{
    /**
     * Create a new patient with an associated user.
     */
    public function create(PatientDTO $data, int $userId = null): Patient
    {
        // If user is not provided, create one
        if (!$userId) {
            // Check if email already exists
            if (User::where('email', $data->email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'This email is already registered.',
                ]);
            }

            $user = User::create([
                'name'     => $data->name,
                'email'    => $data->email,
                'password' => Hash::make($data->password),
            ]);

            // ✅ Assign the role properly via Spatie
            $user->assignRole('patient');
        } else {
            $user = User::findOrFail($userId);
        }

        $patient = Patient::create([
            'user_id' => $user->id,
            'gender'  => strtolower($data->gender),
            'dob'     => $data->dob,
            'address' => $data->address,
            'phone'   => $data->phone,
        ]);

        return $patient->load('user');
    }

    /**
     * Update an existing patient and associated user.
     */
    public function update(int $id, UpdatePatientDTO $dto): Patient
    {
        $patient = Patient::with('user')->findOrFail($id);

        $patient->update([
            'gender'  => strtolower($dto->gender),
            'dob'     => $dto->dob,
            'address' => $dto->address,
            'phone'   => $dto->phone,
        ]);

        $updateUserData = array_filter([
            'name'     => $dto->name,
            'email'    => $dto->email,
        ]);

        if (!empty($updateUserData)) {
            $patient->user->update($updateUserData);
        }

        if (!empty($dto->password)) {
            $patient->user->update([
                'password' => Hash::make($dto->password),
            ]);
        }

        return $patient->load('user');
    }

    /**
     * Delete a patient and the associated user atomically.
     */
    public function delete(Patient $patient): void
    {
        DB::transaction(function () use ($patient) {
            $patient->delete();

            if ($patient->user) {
                $patient->user->delete();
            }
        });
    }

    /**
     * Retrieve all patients with their associated user data.
     */
    public function getAll(): Collection
    {
        return Patient::with('user')->get();
    }

    /**
     * Retrieve a specific patient by ID with user data.
     */
    public function getById(int $id): Patient
    {
        return Patient::with('user')->findOrFail($id);
    }
}
