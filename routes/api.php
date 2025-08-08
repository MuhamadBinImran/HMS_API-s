<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\PatientProfileController;

// Public login route
Route::post('/login', [AuthController::class, 'login']);

// Authenticated routes
Route::middleware(['auth:api'])->group(function () {

    // Common route to get authenticated user info
    Route::get('/me', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => auth()->user()
        ]);
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {

        Route::get('/patients/filter', [PatientController::class, 'filteredIndex']);
        // Patients CRUD
        Route::prefix('patients')->group(function () {
            Route::get('/', [PatientController::class, 'index']);
            Route::post('/', [PatientController::class, 'store']);
            Route::get('/{id}', [PatientController::class, 'show']);
            Route::put('/{id}', [PatientController::class, 'update']);
            Route::delete('/{id}', [PatientController::class, 'destroy']);
        });
        Route::get('doctors/filter', [DoctorController::class, 'filteredIndex']);
        // Doctors CRUD
        Route::prefix('doctors')->group(function () {
            Route::get('/', [DoctorController::class, 'index']);
            Route::post('/', [DoctorController::class, 'store']);
            Route::get('/{id}', [DoctorController::class, 'show']);
            Route::put('/{id}', [DoctorController::class, 'update']);
            Route::delete('/{id}', [DoctorController::class, 'destroy']);
        });

        // Appointment approval/rejection
        Route::prefix('appointments')->group(function () {
            Route::put('/{id}/approve', [AppointmentController::class, 'approve']);
            Route::put('/{id}/reject', [AppointmentController::class, 'reject']);
            Route::delete('/{id}', [AppointmentController::class, 'destroy']);
        });

        // Medicines CRUD + Excel Export
        Route::prefix('medicines')->group(function () {
            Route::get('/export', [MedicineController::class, 'export']);
            Route::get('/', [MedicineController::class, 'index']);
            Route::post('/', [MedicineController::class, 'store']);
            Route::get('/{id}', [MedicineController::class, 'show']);
            Route::put('/{id}', [MedicineController::class, 'update']);
            Route::delete('/{id}', [MedicineController::class, 'destroy']);
        });

        // Bills Management
        Route::prefix('bills')->group(function () {
            Route::post('/', [BillController::class, 'store']);
            Route::put('/{bill}/status', [BillController::class, 'updateStatus']);
            Route::get('/', [BillController::class, 'index']);
            Route::get('/{bill}', [BillController::class, 'show']);
            Route::delete('/{bill}', [BillController::class, 'destroy']);
        });
    });

    // Shared route for both admin & patient
    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::get('/{id}', [AppointmentController::class, 'show']);
    });

    // Patient-only routes: View/Update Profile
    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient/profile', [PatientProfileController::class, 'show']);
        Route::put('/patient/profile/update', [PatientProfileController::class, 'update']);
        Route::get('/patient/medical-history', [AppointmentController::class, 'medicalHistory']);
        Route::get('/patient/bills', [BillController::class, 'patientBills']);
        Route::get('/patient/appointments/{appointment}/prescription', [\App\Http\Controllers\PrescriptionController::class, 'showByAppointment']);
        Route::get('/patient/appointments/{appointment}/bill', [\App\Http\Controllers\Api\BillController::class, 'showByAppointment']);


    });

    // Doctor-only routes: Manage prescriptions
    Route::middleware(['role:doctor'])->group(function () {
        Route::post('/prescriptions', [\App\Http\Controllers\PrescriptionController::class, 'store']);
        Route::get('/doctor/appointments', [AppointmentController::class, 'doctorAppointments']);
        Route::get('/doctor/prescriptions', [\App\Http\Controllers\PrescriptionController::class, 'doctorPrescriptions']);
        Route::get('/doctor/profile', [DoctorController::class, 'profile']);
        Route::put('/doctor/profile/update', [DoctorController::class, 'updateProfile']);

        // Doctor-only appointment approval/rejection
        Route::put('/doctor/appointments/{id}/approve', [AppointmentController::class, 'doctorApprove']);
        Route::put('/doctor/appointments/{id}/reject', [AppointmentController::class, 'doctorReject']);


    });

    Route::middleware(['auth:api', 'role:admin'])->group(function () {
        // Existing patient CRUD routes...

        // New filtered route
    });
});
