<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $properties = Property::with('user')->latest()->get();
        return response()->json($properties);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validated($request);

        $property = Property::create($validated);

        return response()->json($property, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $property = Property::with('user')->findOrFail($id);
        
        return response()->json($property);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $property = Property::findOrFail($id);

        $validated = $this->validated($request);

        $property->update($validated);

        return response()->json($property);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->json([
            'message' => 'Property deleted successfully'
        ], 200); 
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'description' => 'nullable|string',
        ]);
    }
}
