<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomRental;
use App\Models\Room;

class RoomRentalController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomRental::query();
        if ($request->filled('search')) {
            $query->where('rental_type', 'like', '%' . $request->search . '%');
        }
        $roomRentals = $query->latest()->paginate(10);
        return view('admin.room-rentals.index', compact('roomRentals'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('admin.room-rentals.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'rental_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        RoomRental::create($validated);

        return redirect()->route('admin.room-rentals.index')->with('success', 'Tipe sewa berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        return view('admin.room-rentals.show', compact('id'));
    }

    public function edit(string $id)
    {
        return view('admin.room-rentals.edit', compact('id'));
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
