<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class DashboardController extends Controller
{


    public function index(Request $request)
    {

        // DATE RANGE

        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::now()->subDays(6);

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $endDate = $endDate->endOfDay();


        //  CARD STATISTIK

        $totalUsers = User::where('role', 'member')->count();
        $totalProducts = Product::count();
        $activeRentals = Transaction::where('status', 'active')->count();
        $totalRevenue = Transaction::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $latestUsers = User::where('role', 'member')
            ->latest()
            ->limit(3)
            ->get();

        //  GRAFIK DINAMIS

        $revenues = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        $period = Carbon::parse($startDate)->daysUntil($endDate);

        foreach ($period as $date) {
            $formatted = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('d M');

            $found = $revenues->firstWhere('date', $formatted);
            $data[] = $found ? $found->total : 0;
        }

        return view('admin.index', compact(
            'totalUsers',
            'totalProducts',
            'activeRentals',
            'totalRevenue',
            'latestUsers',
            'labels',
            'data',
            'startDate',
            'endDate'
        ));
    }
}
