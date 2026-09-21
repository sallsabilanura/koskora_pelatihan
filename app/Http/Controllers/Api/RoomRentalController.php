<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomRental;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomRentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $roomRentals = RoomRental::with('room')->latest()->get();
        return response()->json($roomRentals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validated($request);

        $roomRental = RoomRental::create($validated);

        return response()->json($roomRental, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $roomRental = RoomRental::with('room')->findOrFail($id);
        
        return response()->json($roomRental);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $roomRental = RoomRental::findOrFail($id);

        $validated = $this->validated($request);

        $roomRental->update($validated);

        return response()->json($roomRental);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $roomRental = RoomRental::findOrFail($id);
        $roomRental->delete();

        return response()->json([
            'message' => 'RoomRental deleted successfully'
        ], 200); 
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'rental_type' => 'required|string',
            'price' => 'required|numeric',
        ]);
    }
}
