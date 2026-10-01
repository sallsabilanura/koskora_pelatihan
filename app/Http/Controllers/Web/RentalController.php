<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\RoomRental;
use App\Models\User;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['user', 'roomRental.room'])->sortable()->paginate(10);
        $users = User::where('role', 'tenant')->get();
        $roomRentals = RoomRental::with('room')->get();

        return view('admin.rentals.index', compact('rentals', 'users', 'roomRentals'));
    }

    public function create()
    {
        $users = User::where('role', 'tenant')->where('is_active', true)->get();
        // Only get room rentals for available rooms
        $roomRentals = RoomRental::whereHas('room', function ($q) {
            $q->where('status', 'available');
        })->with('room')->get();

        return view('admin.rentals.create', compact('users', 'roomRentals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_rentals_id' => 'required|exists:room_rentals,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rental_price' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['status'] = 'active';

        $rental = Rental::create($data);

        if ($rental->status === 'active') {
            $roomRental = RoomRental::with('room')->find($rental->room_rentals_id);
            if ($roomRental && $roomRental->room) {
                $roomRental->room->update(['status' => 'occupied']);
            }
        }

        return redirect()->route('admin.rentals.index')->with('success', 'Kontrak sewa berhasil dibuat');
    }

    public function show(string $id)
    {
        $rental = Rental::with(['user', 'roomRental.room'])->findOrFail($id);

        return view('admin.rentals.show', compact('rental'));
    }

    public function edit(string $id)
    {
        $rental = Rental::findOrFail($id);
        $users = User::where('role', 'tenant')->get();

        // Show rentals for available rooms OR the room currently attached to this contract
        $roomRentals = RoomRental::whereHas('room', function ($q) {
            $q->where('status', 'available');
        })->orWhere('id', $rental->room_rentals_id)
            ->with('room')->get();

        return view('admin.rentals.edit', compact('rental', 'users', 'roomRentals'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_rentals_id' => 'required|exists:room_rentals,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rental_price' => 'required|numeric|min:0',
        ]);

        $rental = Rental::findOrFail($id);
        $oldRoomRentalId = $rental->room_rentals_id;

        $data = $request->except('status');
        $rental->update($data);

        // Update current room
        $currentRoomRental = RoomRental::with('room')->find($rental->room_rentals_id);
        if ($currentRoomRental && $currentRoomRental->room) {
            if ($rental->status === 'active') {
                $currentRoomRental->room->update(['status' => 'occupied']);
            } else {
                $currentRoomRental->room->update(['status' => 'available']);
            }
        }

        // If room changed, free up the old room
        if ($oldRoomRentalId !== $rental->room_rentals_id) {
            $oldRoomRental = RoomRental::with('room')->find($oldRoomRentalId);
            if ($oldRoomRental && $oldRoomRental->room) {
                $oldRoomRental->room->update(['status' => 'available']);
            }
        }

        return redirect()->route('admin.rentals.index')->with('success', 'Kontrak sewa berhasil diperbarui');
    }

    public function toggleStatus(string $id)
    {
        $rental = Rental::findOrFail($id);
        $newStatus = $rental->status === 'active' ? 'inactive' : 'active';
        $rental->update(['status' => $newStatus]);

        $roomRental = RoomRental::with('room')->find($rental->room_rentals_id);
        if ($roomRental && $roomRental->room) {
            if ($newStatus === 'active') {
                $roomRental->room->update(['status' => 'occupied']);
            } else {
                $roomRental->room->update(['status' => 'available']);
            }
        }

        return redirect()->back()->with('success', 'Status sewa berhasil diubah');
    }

    public function destroy(string $id)
    {
        $rental = Rental::findOrFail($id);

        $roomRental = RoomRental::with('room')->find($rental->room_rentals_id);
        if ($roomRental && $roomRental->room) {
            $roomRental->room->update(['status' => 'available']);
        }

        $rental->delete();

        return redirect()->route('admin.rentals.index')->with('success', 'Kontrak sewa berhasil dihapus');
    }
}
