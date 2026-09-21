<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = collect([]);
        return view('admin.rentals.index', compact('rentals'));
    }

    public function create()
    {
        return view('admin.rentals.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        return view('admin.rentals.show', compact('id'));
    }

    public function edit(string $id)
    {
        return view('admin.rentals.edit', compact('id'));
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
