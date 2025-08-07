<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PatientProfileController extends Controller
{
    /**
     * Show the authenticated patient's profile.
     */
    public function show()
    {
        $user = Auth::user();
        $patientProfile = $user->patientProfile;

        if (!$patientProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Patient profile not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'roles' => $user->roles, // assuming loaded
                ],
                'profile' => [
                    'id' => $patientProfile->id,
                    'user_id' => $patientProfile->user_id,
                    'gender' => $patientProfile->gender,
                    'dob' => $patientProfile->dob,
                    'address' => $patientProfile->address,
                    'phone' => $patientProfile->phone,
                    'created_at' => $patientProfile->created_at,
                    'updated_at' => $patientProfile->updated_at,
                ]
            ]
        ]);
    }

    /**
     * Update the authenticated patient's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patientProfile;

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient profile not found.',
            ], 404);
        }

        // Validation
        $request->validate([
            'name'    => 'sometimes|string|max:255',
            'phone'   => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:255',
            'gender'  => 'sometimes|in:male,female,other',
            'dob'     => 'sometimes|date|before:today',
        ]);

        // Update name if provided
        if ($request->filled('name')) {
            $user->name = $request->name;
            $user->save();
        }

        // Update patient profile fields
        $patient->update($request->only(['phone', 'address', 'gender', 'dob']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'roles' => $user->roles,
                ],
                'profile' => [
                    'id' => $patient->id,
                    'user_id' => $patient->user_id,
                    'gender' => $patient->gender,
                    'dob' => $patient->dob,
                    'address' => $patient->address,
                    'phone' => $patient->phone,
                    'created_at' => $patient->created_at,
                    'updated_at' => $patient->updated_at,
                ]
            ]
        ]);
    }
}
