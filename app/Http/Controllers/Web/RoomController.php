<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Room;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['property', 'roomRentals']);
        
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->latest()->paginate(10);
        $districts = collect([]); // mock until District model is clear
        
        return view('admin.rooms.index', compact('rooms', 'districts'));
    }

    public function create()
    {
        $properties = Property::all();
        $facilities = Facility::all();
        return view('admin.rooms.create', compact('properties', 'facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:255',
            'floor' => 'required|string|max:255',
            'properties_id' => 'required|exists:properties,id',
            'facilities_id' => 'required|exists:facilities,id',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        return view('admin.rooms.show', compact('id'));
    }

    public function edit(string $id)
    {
        return view('admin.rooms.edit', compact('id'));
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
