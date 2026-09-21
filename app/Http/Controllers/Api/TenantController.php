<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tenants = Tenant::with('user')->latest()->get();
        return response()->json($tenants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Optional: Ensure only owner or admin can register a tenant
        if ($request->user() && $request->user()->role === 'tenant') {
            return response()->json(['message' => 'Unauthorized. Only owners can register a tenant.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'emergency_contact' => 'nullable|string|max:20',
            'status' => 'required|string',
        ]);

        // Create the user first (role = tenant). Use phone number to generate dummy email
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['phone_number'] . '@tenant.local',
            'password' => Hash::make(\Illuminate\Support\Str::random(16)),
            'role' => 'tenant',
        ]);

        $tenant = Tenant::create([
            'user_id' => $user->id,
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'emergency_contact' => $validated['emergency_contact'],
            'status' => $validated['status'],
        ]);

        $tenant->load('user');

        return response()->json($tenant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $tenant = Tenant::with('user')->findOrFail($id);
        
        return response()->json($tenant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);

        $validated = $request->validate([
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'emergency_contact' => 'nullable|string|max:20',
            'status' => 'required|string',
        ]);

        $tenant->update($validated);
        
        // Optional: Update user details (name) if passed in the request
        if ($request->has('name')) {
            $userData = $request->validate([
                'name' => 'sometimes|string|max:255',
            ]);
            $tenant->user->update($userData);
        }

        $tenant->load('user');

        return response()->json($tenant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);
        
        // Delete associated user as well
        if ($tenant->user) {
            $tenant->user->delete();
        }
        
        $tenant->delete();

        return response()->json([
            'message' => 'Tenant and associated user deleted successfully'
        ], 200); 
    }
}
