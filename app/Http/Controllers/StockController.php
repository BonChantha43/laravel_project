<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    // ១. បង្ហាញ Form នាំចូលស្តុក
    public function stockInForm()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('admin.stock.in', compact('products', 'suppliers'));
    }

    // ២. រក្សាទុកការនាំចូល (Save Stock In)
    public function processStockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        // ក. បង្កើត Transaction (កត់ត្រាប្រវត្តិ)
        StockTransaction::create([
            'product_id' => $product->id,
            'user_id' => Auth::id() ?? 1,
            'supplier_id' => $request->supplier_id,
            'type' => 'in', // ប្រភេទនាំចូល
            'qty' => $request->qty,
            'date' => now(),
        ]);

        // ខ. បូកស្តុកចូលក្នុងទំនិញ (Update Product Qty)
        $product->increment('qty', $request->qty);

        return redirect()->back()->with('success', 'នាំចូលស្តុកបានជោគជ័យ! បូកបន្ថែម ' . $request->qty . ' ឯកតា។');
    }
}