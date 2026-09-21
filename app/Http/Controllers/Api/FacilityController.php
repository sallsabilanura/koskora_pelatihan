<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::latest()->get();
        return response()->json($facilities);
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request): JsonResponse
    {
        // Memanggil private function untuk validasi
        $validated = $this->validated($request);

        $facility = Facility::create($validated);

        return response()->json($facility, 201);
    }


    /**
     * Display the specified resource.
     */
   public function show(string $id): JsonResponse
    {
        $facility = Facility::findOrFail($id);
        
        return response()->json($facility);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, string $id): JsonResponse
    {
        $facility = Facility::findOrFail($id);

        // Kita juga bisa menggunakan fungsi validasi yang sama untuk update
        $validated = $this->validated($request);

        $facility->update($validated);

        return response()->json($facility);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(string $id): JsonResponse
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return response()->json([
            'message' => 'Facility deleted successfully'
        ], 200); 
    }

     private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
    }
}
