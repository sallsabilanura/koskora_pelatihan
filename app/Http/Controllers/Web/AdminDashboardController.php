<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Ensure only admin can access this page
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admins only.');
        }

        return view('admin.dashboard');
    }
}
