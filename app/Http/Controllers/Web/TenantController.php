<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = \App\Models\Tenant::with('user')->sortable()->paginate(10);
        $users = \App\Models\User::where('role', 'tenant')->get();
        return view('admin.tenants.index', compact('tenants', 'users'));
    }

    public function create()
    {
        $users = \App\Models\User::where('role', 'tenant')->get();
        return view('admin.tenants.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'emergency_contact' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        \App\Models\Tenant::create($request->all());

        return redirect()->route('admin.tenants.index')->with('success', 'Penyewa berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);
        return view('admin.tenants.show', compact('tenant'));
    }

    public function edit(string $id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);
        $users = \App\Models\User::where('role', 'tenant')->get();
        return view('admin.tenants.edit', compact('tenant', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'emergency_contact' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $tenant = \App\Models\Tenant::findOrFail($id);
        $tenant->update($request->all());

        return redirect()->route('admin.tenants.index')->with('success', 'Data penyewa berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);
        $tenant->delete();
        
        return redirect()->route('admin.tenants.index')->with('success', 'Penyewa berhasil dihapus');
    }
}
