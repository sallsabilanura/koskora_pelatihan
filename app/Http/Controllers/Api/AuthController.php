<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'owner', // Only owners can register via this endpoint
        ]);

        // Create Sanctum token for mobile
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Owner registered successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Authenticate an owner.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Request OTP for Tenant Login
     */
    public function requestOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => 'required|string'
        ]);

        // Find tenant user by phone number
        $user = \App\Models\User::where('phone_number', $request->phone_number)->where('role', 'tenant')->first();

        if (!$user) {
            return response()->json(['message' => 'Phone number not found in our records.'], 404);
        }

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Save OTP to Cache for 5 minutes (300 seconds)
        // Note: Cache::put doesn't require importing if we use the \Illuminate\Support\Facades\Cache facade
        \Illuminate\Support\Facades\Cache::put('otp_' . $request->phone_number, $otp, 300);

        // TODO: In a real app, send this OTP via SMS/WhatsApp using a provider (e.g., Twilio, Watzap)
        // For now, we will return it in the response so you can test it
        return response()->json([
            'message' => 'OTP sent successfully',
            'dev_note' => 'In production, do not return OTP here. Send via SMS.',
            'otp' => $otp
        ]);
    }

    /**
     * Verify OTP and Login Tenant
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => 'required|string',
            'otp' => 'required|string'
        ]);

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $request->phone_number);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 401);
        }

        $user = \App\Models\User::where('phone_number', $request->phone_number)->where('role', 'tenant')->first();

        // Clear the OTP
        \Illuminate\Support\Facades\Cache::forget('otp_' . $request->phone_number);

        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Tenant logged in successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request): JsonResponse
    {
        // For Sanctum:
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }
}
