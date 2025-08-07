<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Create roles for 'api' guard
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'api']);

        // ✅ Create admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // ✅ Create doctor 1
        $doctorUser1 = User::create([
            'name' => 'Doctor One',
            'email' => 'doctor1@example.com',
            'password' => Hash::make('password'),
        ]);
        $doctorUser1->assignRole('doctor');

        Doctor::create([
            'user_id' => $doctorUser1->id,
            'specialization' => 'Cardiology',
            'phone' => '03001234567',
            'address' => 'Doctor Street 1',
        ]);

        // ✅ Create doctor 2
        $doctorUser2 = User::create([
            'name' => 'Doctor Two',
            'email' => 'doctor2@example.com',
            'password' => Hash::make('password'),
        ]);
        $doctorUser2->assignRole('doctor');

        Doctor::create([
            'user_id' => $doctorUser2->id,
            'specialization' => 'Neurology',
            'phone' => '03007651234',
            'address' => 'Doctor Street 2',
        ]);

        // ✅ Create patient 1
        $patientUser1 = User::create([
            'name' => 'Patient One',
            'email' => 'patient1@example.com',
            'password' => Hash::make('password'),
        ]);
        $patientUser1->assignRole('patient');

        Patient::create([
            'user_id' => $patientUser1->id,
            'gender' => 'Male',
            'dob' => '1999-01-01',
            'phone' => '03007654321',
            'address' => 'Patient Avenue 1',
        ]);

        // ✅ Create patient 2
        $patientUser2 = User::create([
            'name' => 'Patient Two',
            'email' => 'patient2@example.com',
            'password' => Hash::make('password'),
        ]);
        $patientUser2->assignRole('patient');

        Patient::create([
            'user_id' => $patientUser2->id,
            'gender' => 'Female',
            'dob' => '2000-05-15',
            'phone' => '03004561234',
            'address' => 'Patient Avenue 2',
        ]);
    }
}
