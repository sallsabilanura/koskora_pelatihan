<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $perPage = request()->input('per_page', 10);
        $payments = \App\Models\Payment::with(['rental.user', 'rental.roomRental.room'])->sortable()->paginate($perPage);
        $rentals = \App\Models\Rental::with(['user', 'roomRental.room'])->get();
        return view('admin.payments.index', compact('payments', 'rentals'));
    }

    public function create()
    {
        $rentals = \App\Models\Rental::with(['user', 'roomRental.room'])->where('status', 'active')->get();
        return view('admin.payments.create', compact('rentals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rentals_id' => 'required|exists:rentals,id',
            'payment_date' => 'required|date',
            'payment_period' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        \App\Models\Payment::create($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil dicatat');
    }

    public function show(string $id)
    {
        $payment = \App\Models\Payment::with(['rental.user', 'rental.roomRental.room'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(string $id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $rentals = \App\Models\Rental::with(['user', 'roomRental.room'])->get();
        return view('admin.payments.edit', compact('payment', 'rentals'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'rentals_id' => 'required|exists:rentals,id',
            'payment_date' => 'required|date',
            'payment_period' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        $payment = \App\Models\Payment::findOrFail($id);
        $payment->update($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Data pembayaran berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Riwayat pembayaran berhasil dihapus');
    }
}
