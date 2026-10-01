<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['user', 'rooms'])->withCount('rooms');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('address', 'like', '%'.$request->search.'%');
        }

        $properties = $query->sortable()->paginate(10);
        $users = User::all();

        return view('admin.properties.index', compact('properties', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Property::create($validated);

        return redirect()->route('admin.properties.index')->with('success', 'Properti berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $property = Property::findOrFail($id);
        $property->update($validated);

        return redirect()->route('admin.properties.index')->with('success', 'Properti berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return redirect()->route('admin.properties.index')->with('success', 'Properti berhasil dihapus.');
    }
}
