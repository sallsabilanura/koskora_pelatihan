<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = collect([]);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        return view('admin.tenants.show', compact('id'));
    }

    public function edit(string $id)
    {
        return view('admin.tenants.edit', compact('id'));
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
