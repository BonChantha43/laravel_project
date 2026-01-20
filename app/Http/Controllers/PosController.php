<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Category;

class PosController extends Controller
{
    /**
     * 1. បង្ហាញទំព័រលក់ (POS Page)
     */
    public function index()
    {
        // ទាញយកទំនិញទាំងអស់ (ភ្ជាប់ជាមួយ Category ដើម្បីយកទៅ Filter)
        $products = Product::with('category')->get();

        // ទាញយកប្រភេទទាំងអស់ ដើម្បីយកទៅធ្វើប៊ូតុង Filter នៅខាងលើ
        $categories = Category::all();

        return view('pos.index', compact('products', 'categories'));
    }

    /**
     * 2. ដំណើរការលក់ និងកាត់ស្តុក (API)
     */
    public function storeSale(Request $request)
    {
        // Validate ទិន្នន័យ
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'final_total' => 'required|numeric',
            'discount' => 'nullable|numeric',
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // ១. បង្កើតវិក្កយបត្រ (Sale Header)
                $sale = Sale::create([
                    'invoice_number' => 'INV-' . time(),
                    'user_id' => Auth::id() ?? 1,
                    'total_amount' => $request->total_amount,
                    'discount' => $request->discount ?? 0,
                    'final_total' => $request->final_total,
                    'payment_type' => $request->payment_type ?? 'cash',
                    // បានលុប 'status' ចេញ
                ]);

                // ២. Loop មុខទំនិញនីមួយៗ
                foreach ($request->items as $item) {
                    $product = Product::find($item['id']);

                    // Check ស្តុក
                    if ($product->qty < $item['qty']) {
                        throw new \Exception("ទំនិញ {$product->name} នៅសល់តែ {$product->qty} ប៉ុណ្ណោះ!");
                    }

                    // ក. បញ្ចូល Sale Detail
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'qty' => $item['qty'],
                        'price' => $product->sale_price,
                        'subtotal' => $item['qty'] * $product->sale_price,
                    ]);

                    // ខ. កាត់ស្តុកចេញ
                    $product->decrement('qty', $item['qty']);

                    // គ. កត់ត្រាប្រវត្តិស្តុក
                    StockTransaction::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id() ?? 1,
                        'type' => 'sale',
                        'qty' => -$item['qty'],
                        'date' => now(),
                        // ❌ បានលុប 'description' ចេញ ដើម្បីកុំឱ្យ Error Database
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'ការលក់ជោគជ័យ!',
                    'data' => $sale
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
