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

        $totalRooms = \App\Models\Room::count();
        $availableRooms = \App\Models\Room::where('status', 'available')->count();
        $totalTenants = \App\Models\Tenant::count();
        $totalRevenue = \App\Models\Payment::where('status', 'paid')->sum('amount');
        
        $recentPayments = \App\Models\Payment::with(['rental.tenant', 'rental.roomRental.room'])->latest()->take(5)->get();
        $announcementsCount = 0;

        return view('admin.dashboard', compact(
            'totalRooms',
            'availableRooms',
            'totalTenants',
            'totalRevenue',
            'recentPayments',
            'announcementsCount'
        ));
    }
}
