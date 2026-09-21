<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = collect([]);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        return view('admin.payments.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        return view('admin.payments.show', compact('id'));
    }

    public function edit(string $id)
    {
        return view('admin.payments.edit', compact('id'));
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
