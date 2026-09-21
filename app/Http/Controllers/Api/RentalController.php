<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $rentals = Rental::with(['tenant', 'roomRental'])->latest()->get();
        return response()->json($rentals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validated($request);

        $rental = Rental::create($validated);

        return response()->json($rental, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $rental = Rental::with(['tenant', 'roomRental'])->findOrFail($id);
        
        return response()->json($rental);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $rental = Rental::findOrFail($id);

        $validated = $this->validated($request);

        $rental->update($validated);

        return response()->json($rental);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $rental = Rental::findOrFail($id);
        $rental->delete();

        return response()->json([
            'message' => 'Rental deleted successfully'
        ], 200); 
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tenants_id' => 'required|exists:tenants,id',
            'room_rentals_id' => 'required|exists:room_rentals,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'rental_price' => 'required|numeric',
            'status' => 'required|string',
        ]);
    }
}
