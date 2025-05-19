<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Order;
use App\Models\FuelRecord;
use App\Models\ServiceRecord;
use App\Models\UsageRecord;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Greeting umum
        $greeting = 'Selamat datang, ' . $user->name;

        // Data summary umum
        $totalVehicles = Vehicle::count();
        $fuelUsage     = FuelRecord::sum('fuel_amount');
        $serviceCount  = ServiceRecord::count();

        // Data grafik bulanan (per bulan 1-12)
        $fuelUsageMonthly = FuelRecord::selectRaw('MONTH(date) as month, SUM(fuel_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        $serviceCountMonthly = ServiceRecord::selectRaw('MONTH(service_date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        $vehicleUsageMonthly = UsageRecord::selectRaw('MONTH(date) as month, SUM(distance) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // Susun data bulanan dalam array 12 bulan
        $months      = range(1, 12);
        $fuelData    = [];
        $serviceData = [];
        $usageData   = [];

        foreach ($months as $month) {
            $fuelData[]    = $fuelUsageMonthly[$month] ?? 0;
            $serviceData[] = $serviceCountMonthly[$month] ?? 0;
            $usageData[]   = $vehicleUsageMonthly[$month] ?? 0;
        }

        // Tambahan khusus untuk approver: ambil permintaan kendaraan yang menunggu persetujuan
        $approvalOrders = [];
        if (in_array($user->role, ['approver1', 'approver2'])) {
            $approvalOrders = Order::where('status', 'pending')->with('user')->get();
        }

        // Kirim semua data ke view
        return view('dashboard.index', compact(
            'greeting',
            'totalVehicles',
            'fuelUsage',
            'serviceCount',
            'fuelData',
            'serviceData',
            'usageData',
            'approvalOrders'
        ));
    }
}
