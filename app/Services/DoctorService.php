<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\User;
use App\DTOs\DoctorDTO;
use App\DTOs\UpdateDoctorDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    /**
     * Create a new doctor with associated user.
     */
    public function create(DoctorDTO $data): Doctor
    {
        return DB::transaction(function () use ($data) {
            $user = $this->createUserForDoctor($data);

            $doctor = Doctor::create([
                'user_id'        => $user->id,
                'specialization' => $data->specialization,
                'phone'          => $data->phone,
                'address'        => $data->address,
            ]);

            return $doctor->load('user');
        });
    }

    /**
     * Helper method to create user with 'doctor' role.
     */
    private function createUserForDoctor(DoctorDTO $data): User
    {
        return User::create([
            'name'     => $data->name,
            'email'    => $data->email,
            'password' => Hash::make($data->password),
            'role'     => 'doctor', // Explicitly assign doctor role
        ]);
    }

    /**
     * Update doctor and associated user info.
     */
    public function update(int $id, UpdateDoctorDTO $dto): Doctor
    {
        return DB::transaction(function () use ($id, $dto) {
            $doctor = Doctor::with('user')->findOrFail($id);

            $doctor->update([
                'specialization' => $dto->specialization,
                'phone'          => $dto->phone,
                'address'        => $dto->address,
            ]);

            if ($doctor->user) {
                $doctor->user->update([
                    'name'  => $dto->name,
                    'email' => $dto->email,
                ]);
            }

            return $doctor->load('user');
        });
    }

    /**
     * Delete doctor and associated user.
     */
    public function delete(Doctor $doctor): void
    {
        DB::transaction(function () use ($doctor) {
            $user = $doctor->user;

            $doctor->delete();

            if ($user) {
                $user->delete();
            }
        });
    }

    /**
     * Get all doctors with their associated users.
     */
    public function getAll(): Collection
    {
        return Doctor::with('user')->get();
    }

    /**
     * Get a doctor by ID with user data.
     */
    public function getById(int $id): Doctor
    {
        return Doctor::with('user')->findOrFail($id);
    }
}
