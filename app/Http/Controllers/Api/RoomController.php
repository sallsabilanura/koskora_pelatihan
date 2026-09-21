<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $rooms = Room::with(['facility', 'property'])->latest()->get();
        return response()->json($rooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validated($request);

        $room = Room::create($validated);

        return response()->json($room, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $room = Room::with(['facility', 'property'])->findOrFail($id);
        
        return response()->json($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $room = Room::findOrFail($id);

        $validated = $this->validated($request);

        $room->update($validated);

        return response()->json($room);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json([
            'message' => 'Room deleted successfully'
        ], 200); 
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'facilities_id' => 'required|exists:facilities,id',
            'properties_id' => 'required|exists:properties,id',
            'room_number' => 'required|string',
            'floor' => 'required|string',
            'status' => 'required|string',
        ]);
    }
}
