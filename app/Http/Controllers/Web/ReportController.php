<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.reports.index');
    }

    public function print(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $payments = Payment::with(['rental.tenant', 'rental.roomRental.room'])
            ->whereBetween('payment_date', [$start, $end])
            ->where('status', 'paid')
            ->orderBy('payment_date', 'asc')
            ->get();

        $totalRevenue = $payments->sum('amount');

        return view('admin.reports.print', compact('payments', 'month', 'totalRevenue'));
    }
}
