<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = \App\Models\Property::with('user')->latest()->paginate(10);
        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        return view('admin.properties.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
        ]);

        \App\Models\Property::create($validated);

        return redirect()->route('admin.properties.index')->with('success', 'Properti berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $property = \App\Models\Property::with('user')->findOrFail($id);
        return view('admin.properties.show', compact('property'));
    }

    public function edit(string $id)
    {
        $property = \App\Models\Property::findOrFail($id);
        $users = \App\Models\User::all();
        return view('admin.properties.edit', compact('property', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $property = \App\Models\Property::findOrFail($id);
        $property->update($validated);

        return redirect()->route('admin.properties.index')->with('success', 'Properti berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $property = \App\Models\Property::findOrFail($id);
        $property->delete();

        return redirect()->route('admin.properties.index')->with('success', 'Properti berhasil dihapus.');
    }
}
