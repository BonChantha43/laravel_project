<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ១. រកចំណូលសរុប និង ចំណូលថ្ងៃនេះ
        $totalSales = Sale::sum('final_total');
        
        $todaySales = Sale::whereDate('created_at', Carbon::today())
                          ->sum('final_total');

        // ២. ចំនួនទំនិញសរុប
        $totalProducts = Product::count();

        // ៣. ចំនួនទំនិញជិតអស់ស្តុក (ដាក់ឈ្មោះថា lowStockCount ដើម្បីឱ្យត្រូវនឹង View)
        // យកលក្ខខណ្ឌ < 5 ឬ < 10 តាមចិត្តបង (នៅទីនេះខ្ញុំដាក់ <= 5)
        $lowStockCount = Product::where('qty', '<=', 5)->count();

        // ៤. ទិន្នន័យសម្រាប់ធ្វើ Graph (លក់ ៧ ថ្ងៃចុងក្រោយ) - Optional
        // ផ្នែកនេះល្អណាស់សម្រាប់ប្រើជាមួយ Chart.js នៅថ្ងៃក្រោយ
        $salesData = Sale::selectRaw('DATE(created_at) as date, SUM(final_total) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $salesData->pluck('date');
        $totals = $salesData->pluck('total');

        // បញ្ជូនទិន្នន័យទៅ View
        return view('admin.dashboard', compact(
            'totalSales', 
            'todaySales', 
            'totalProducts', 
            'lowStockCount', // ឈ្មោះនេះត្រូវតែដូចគ្នានៅក្នុង dashboard.blade.php
            'dates', 
            'totals'
        ));
    }
}