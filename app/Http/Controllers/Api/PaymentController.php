<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $payments = Payment::with('rental')->latest()->get();
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validated($request);

        $payment = Payment::create($validated);

        return response()->json($payment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $payment = Payment::with('rental')->findOrFail($id);
        
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        $validated = $this->validated($request);

        $payment->update($validated);

        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted successfully'
        ], 200); 
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'rentals_id' => 'required|exists:rentals,id',
            'payment_date' => 'required|date',
            'payment_period' => 'required|string',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'status' => 'required|string',
        ]);
    }
}
