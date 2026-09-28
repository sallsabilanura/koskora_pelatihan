<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = \App\Models\Rental::with(['tenant.user', 'roomRental.room'])->sortable()->paginate(10);
        $tenants = \App\Models\Tenant::with('user')->get();
        $roomRentals = \App\Models\RoomRental::with('room')->get();
        return view('admin.rentals.index', compact('rentals', 'tenants', 'roomRentals'));
    }

    public function create()
    {
        $tenants = \App\Models\Tenant::with('user')->where('status', 'active')->get();
        // Only get room rentals for available rooms
        $roomRentals = \App\Models\RoomRental::whereHas('room', function($q) {
            $q->where('status', 'available');
        })->with('room')->get();
        
        return view('admin.rentals.create', compact('tenants', 'roomRentals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenants_id' => 'required|exists:tenants,id',
            'room_rentals_id' => 'required|exists:room_rentals,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rental_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $rental = \App\Models\Rental::create($request->all());

        if ($rental->status === 'active') {
            $roomRental = \App\Models\RoomRental::with('room')->find($rental->room_rentals_id);
            if ($roomRental && $roomRental->room) {
                $roomRental->room->update(['status' => 'occupied']);
            }
        }

        return redirect()->route('admin.rentals.index')->with('success', 'Kontrak sewa berhasil dibuat');
    }

    public function show(string $id)
    {
        $rental = \App\Models\Rental::with(['tenant.user', 'roomRental.room'])->findOrFail($id);
        return view('admin.rentals.show', compact('rental'));
    }

    public function edit(string $id)
    {
        $rental = \App\Models\Rental::findOrFail($id);
        $tenants = \App\Models\Tenant::with('user')->get();
        
        // Show rentals for available rooms OR the room currently attached to this contract
        $roomRentals = \App\Models\RoomRental::whereHas('room', function($q) {
            $q->where('status', 'available');
        })->orWhere('id', $rental->room_rentals_id)
          ->with('room')->get();
          
        return view('admin.rentals.edit', compact('rental', 'tenants', 'roomRentals'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tenants_id' => 'required|exists:tenants,id',
            'room_rentals_id' => 'required|exists:room_rentals,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rental_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $rental = \App\Models\Rental::findOrFail($id);
        $oldStatus = $rental->status;
        $oldRoomRentalId = $rental->room_rentals_id;
        
        $rental->update($request->all());

        // Update current room
        $currentRoomRental = \App\Models\RoomRental::with('room')->find($rental->room_rentals_id);
        if ($currentRoomRental && $currentRoomRental->room) {
            if ($rental->status === 'active') {
                $currentRoomRental->room->update(['status' => 'occupied']);
            } else {
                $currentRoomRental->room->update(['status' => 'available']);
            }
        }

        // If room changed, free up the old room
        if ($oldRoomRentalId !== $rental->room_rentals_id) {
            $oldRoomRental = \App\Models\RoomRental::with('room')->find($oldRoomRentalId);
            if ($oldRoomRental && $oldRoomRental->room) {
                $oldRoomRental->room->update(['status' => 'available']);
            }
        }

        return redirect()->route('admin.rentals.index')->with('success', 'Kontrak sewa berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $rental = \App\Models\Rental::findOrFail($id);
        
        $roomRental = \App\Models\RoomRental::with('room')->find($rental->room_rentals_id);
        if ($roomRental && $roomRental->room) {
            $roomRental->room->update(['status' => 'available']);
        }
        
        $rental->delete();

        return redirect()->route('admin.rentals.index')->with('success', 'Kontrak sewa berhasil dihapus');
    }
}
