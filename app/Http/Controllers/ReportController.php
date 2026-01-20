<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\StockTransaction;
use Carbon\Carbon;

class ReportController extends Controller
{
    // ១. របាយការណ៍លក់ (Sales Report)
    public function sales(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::today()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::today()->format('Y-m-d');

        $sales = Sale::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->with('user')
                    ->latest()
                    ->get();

        $totalSale = $sales->sum('final_total');

        return view('admin.reports.sales', compact('sales', 'totalSale', 'startDate', 'endDate'));
    }

    // ២. របាយការណ៍ស្តុក (Stock Report)
    public function stock()
    {
        $products = Product::with('category')->get();
        
        // គណនាតម្លៃដើមសរុបក្នុងស្តុក
        $totalCost = $products->sum(function($product) {
            return $product->cost_price * $product->qty;
        });

        // គណនាតម្លៃលក់សរុបក្នុងស្តុក (បើលក់អស់បានប៉ុន្មាន)
        $totalSaleValue = $products->sum(function($product) {
            return $product->sale_price * $product->qty;
        });

        return view('admin.reports.stock', compact('products', 'totalCost', 'totalSaleValue'));
    }

    // ៣. របាយការណ៍ចលនាស្តុក (Transactions: In/Out/Broken)
    public function transactions(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);

        // Filter តាមប្រភេទ (In, Out, Broken...)
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter តាមកាលបរិច្ឆេទ
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->latest()->get();

        return view('admin.reports.transactions', compact('transactions'));
    }
}