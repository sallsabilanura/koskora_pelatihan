<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = \App\Models\Facility::latest()->paginate(10);
        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\Facility::create($validated);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $facility = \App\Models\Facility::findOrFail($id);
        return view('admin.facilities.show', compact('facility'));
    }

    public function edit(string $id)
    {
        $facility = \App\Models\Facility::findOrFail($id);
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $facility = \App\Models\Facility::findOrFail($id);
        $facility->update($validated);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $facility = \App\Models\Facility::findOrFail($id);
        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
