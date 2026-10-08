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
        $query = Room::with(['property', 'roomRentals', 'facilities']);
        
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = request()->input('per_page', 10);
        $rooms = $query->sortable()->paginate($perPage);
        $districts = collect([]); // mock until District model is clear
        $properties = Property::all();
        $facilities = Facility::all();
        
        return view('admin.rooms.index', compact('rooms', 'districts', 'properties', 'facilities'));
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
            'properties_id' => 'required|exists:properties,id',
            'room_number' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('rooms')->where(function ($query) use ($request) {
                    return $query->where('properties_id', $request->properties_id);
                })
            ],
            'floor' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,maintenance',
            'room_type' => 'nullable|string|max:255',
            'gender_target' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id'
        ], [
            'room_number.unique' => 'Nomor kamar ini sudah ada di properti yang sama.'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rooms', 'public');
        }

        $facilities = $validated['facilities'] ?? [];
        unset($validated['facilities']);

        $room = Room::create($validated);
        $room->facilities()->sync($facilities);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $room = Room::with(['property', 'facilities', 'roomRentals'])->findOrFail($id);
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        $properties = Property::all();
        $facilities = Facility::all();
        return view('admin.rooms.edit', compact('room', 'properties', 'facilities'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'properties_id' => 'required|exists:properties,id',
            'room_number' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('rooms')->where(function ($query) use ($request) {
                    return $query->where('properties_id', $request->properties_id);
                })->ignore($id)
            ],
            'floor' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,maintenance',
            'room_type' => 'nullable|string|max:255',
            'gender_target' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id'
        ], [
            'room_number.unique' => 'Nomor kamar ini sudah ada di properti yang sama.'
        ]);

        $room = Room::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($room->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($room->image);
            }
            $validated['image'] = $request->file('image')->store('rooms', 'public');
        }

        $facilities = $validated['facilities'] ?? [];
        unset($validated['facilities']);

        $room->update($validated);
        $room->facilities()->sync($facilities);

        return redirect()->route('admin.rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        if ($room->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($room->image);
        }
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Data kamar berhasil dihapus.');
    }
}
