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
        $perPage = request()->input('per_page', 10);
        $rentals = Rental::with(['user', 'roomRental.room'])->sortable()->paginate($perPage);
        $activeTenantIds = Rental::where('status', 'active')->pluck('user_id')->toArray();
        $users = User::where('role', 'tenant')->whereNotIn('id', $activeTenantIds)->get();
        
        // Get room rentals that are available or occupied (so they can be booked for future dates)
        $roomRentals = RoomRental::whereHas('room', function ($q) {
            $q->whereIn('status', ['available', 'occupied']);
        })->with('room')->get();

        // Get booked dates for each room
        $bookedDates = Rental::whereHas('roomRental')
            ->with('roomRental')
            ->where('status', 'active')
            ->get()
            ->groupBy('roomRental.room_id')
            ->map(function($rents) {
                return $rents->map(function($r) {
                    return ['from' => $r->start_date, 'to' => $r->end_date];
                });
            })->toArray();

        return view('admin.rentals.index', compact('rentals', 'users', 'roomRentals', 'bookedDates'));
    }

    public function create()
    {
        $activeTenantIds = Rental::where('status', 'active')->pluck('user_id')->toArray();
        $users = User::where('role', 'tenant')->where('is_active', true)->whereNotIn('id', $activeTenantIds)->get();
        // Get room rentals for available and occupied rooms
        $roomRentals = RoomRental::whereHas('room', function ($q) {
            $q->whereIn('status', ['available', 'occupied']);
        })->with('room')->get();

        return view('admin.rentals.create', compact('users', 'roomRentals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_rentals_id' => 'required|exists:room_rentals,id',
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('room_rentals_id') && $request->filled('end_date')) {
                        $requestedRoomRental = \App\Models\RoomRental::find($request->room_rentals_id);
                        if ($requestedRoomRental) {
                            $overlapping = \App\Models\Rental::whereHas('roomRental', function($q) use ($requestedRoomRental) {
                                $q->where('room_id', $requestedRoomRental->room_id);
                            })
                            ->where('status', 'active')
                            ->where('start_date', '<=', $request->end_date)
                            ->where('end_date', '>=', $value)
                            ->exists();

                            if ($overlapping) {
                                $fail('Kamar ini sudah disewa pada rentang tanggal tersebut.');
                            }
                        }
                    }
                },
            ],
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
        $activeTenantIds = Rental::where('status', 'active')->where('id', '!=', $id)->pluck('user_id')->toArray();
        $users = User::where('role', 'tenant')->whereNotIn('id', $activeTenantIds)->get();

        // Show rentals for available and occupied rooms OR the room currently attached to this contract
        $roomRentals = RoomRental::whereHas('room', function ($q) {
            $q->whereIn('status', ['available', 'occupied']);
        })->orWhere('id', $rental->room_rentals_id)
            ->with('room')->get();

        return view('admin.rentals.edit', compact('rental', 'users', 'roomRentals'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_rentals_id' => 'required|exists:room_rentals,id',
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request, $id) {
                    if ($request->filled('room_rentals_id') && $request->filled('end_date')) {
                        $requestedRoomRental = \App\Models\RoomRental::find($request->room_rentals_id);
                        if ($requestedRoomRental) {
                            $overlapping = \App\Models\Rental::whereHas('roomRental', function($q) use ($requestedRoomRental) {
                                $q->where('room_id', $requestedRoomRental->room_id);
                            })
                            ->where('status', 'active')
                            ->where('id', '!=', $id)
                            ->where('start_date', '<=', $request->end_date)
                            ->where('end_date', '>=', $value)
                            ->exists();

                            if ($overlapping) {
                                $fail('Kamar ini sudah disewa pada rentang tanggal tersebut.');
                            }
                        }
                    }
                },
            ],
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
