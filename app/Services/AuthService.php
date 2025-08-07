<?php

namespace App\Services;

use App\DTOs\LoginDTO;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

class AuthService
{
    /**
     * Handle user login and return JWT token.
     */
    public function login(LoginDTO $dto): JsonResponse
    {
        $credentials = [
            'email'    => $dto->email,
            'password' => $dto->password,
        ];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            return response()->json([
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => JWTAuth::factory()->getTTL() * 60, // in seconds
                'user'         => auth()->user(), // Optional: include user info
            ]);

        } catch (JWTException $e) {
            Log::error('JWT Login Error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }
}
