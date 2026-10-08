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
        $query = Room::with('roomRentals', 'property')->whereHas('roomRentals');
        
        if ($request->filled('search')) {
            $query->whereHas('roomRentals', function($q) use ($request) {
                $q->where('rental_type', 'like', '%' . $request->search . '%');
            })->orWhere('room_number', 'like', '%' . $request->search . '%');
        }
        
        $perPage = request()->input('per_page', 10);
        $rooms = $query->sortable()->paginate($perPage);
        $allRooms = Room::with('property')->get();
        return view('admin.room-rentals.index', compact('rooms', 'allRooms'));
    }

    public function create()
    {
        $rooms = Room::with('property')->get();
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
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'rental_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $rental = RoomRental::findOrFail($id);
        $rental->update($validated);

        return redirect()->route('admin.room-rentals.index')->with('success', 'Tipe sewa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        //
    }
}
