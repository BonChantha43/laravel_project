<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ១. កុំភ្លេចហៅអាមួយនេះ ដើម្បីលុបរូបភាព

class AdminProductController extends Controller
{
    // ១. បង្ហាញបញ្ជីទំនិញទាំងអស់ (Read / List)
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->latest()->get();

        // បន្ថែមបន្ទាត់នេះ ដើម្បីយក Category និង Supplier ទៅបង្ហាញក្នុង Modal
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('admin.products.index', compact('products', 'categories', 'suppliers'));
    }

    // ២. បង្ហាញ Form បង្កើតទំនិញថ្មី (Create Form)
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    // ៣. រក្សាទុកទំនិញថ្មី (Store Logic)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'barcode' => 'required|unique:products,barcode',
            'category_id' => 'required',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'qty' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate រូបភាព
        ]);

        // ១. ចាប់យករូបភាព (Upload Image)
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Upload ទៅក្នុង folder 'products' ក្នុង storage/app/public
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // ២. បង្កើតទំនិញ
        $product = Product::create([
            'name' => $request->name,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'cost_price' => $request->cost_price,
            'sale_price' => $request->sale_price,
            'qty' => $request->qty,
            'image' => $imagePath, // ដាក់ឈ្មោះរូបចូល Database
        ]);

        // ៣. កត់ត្រាចូលស្តុក (Opening Stock)
        if ($request->qty > 0) {
            StockTransaction::create([
                'product_id' => $product->id,
                'user_id' => Auth::id() ?? 1,
                'supplier_id' => $request->supplier_id,
                'type' => 'in',
                'qty' => $request->qty,
                'date' => now(),
            ]);
        }

        return redirect('/admin/products')->with('success', 'បានបង្កើតទំនិញថ្មីជោគជ័យ!');
    }

    // ៤. បង្ហាញ Form កែប្រែទំនិញ (Edit Form)
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    // ៥. រក្សាទុកការកែប្រែ (Update Logic)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'barcode' => 'required|unique:products,barcode,' . $id,
            'category_id' => 'required',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate រូបភាព
        ]);

        $product = Product::findOrFail($id);

        // រៀបចំទិន្នន័យសម្រាប់ Update
        $data = [
            'name' => $request->name,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'cost_price' => $request->cost_price,
            'sale_price' => $request->sale_price,
            // 'qty' => $request->qty // មិន update qty នៅទីនេះទេ
        ];

        // ពិនិត្យមើលថាមានការ Upload រូបថ្មីឬអត់?
        if ($request->hasFile('image')) {
            // ១. លុបរូបចាស់ចោល (បើមាន)
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // ២. Upload រូបថ្មី
            $imagePath = $request->file('image')->store('products', 'public');

            // ៣. បញ្ចូល path ថ្មីទៅក្នុងទិន្នន័យ
            $data['image'] = $imagePath;
        }

        // Update ចូល Database
        $product->update($data);

        return redirect('/admin/products')->with('success', 'កែប្រែទំនិញជោគជ័យ!');
    }

    // ៦. លុបទំនិញ (Delete Logic)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // ១. លុបទិន្នន័យដែលពាក់ព័ន្ធ
        StockTransaction::where('product_id', $id)->delete();
        SaleDetail::where('product_id', $id)->delete();

        // ២. លុបរូបភាពចោលពី Storage (បើមាន)
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // ៣. លុបទំនិញ
        $product->delete();

        return back()->with('success', 'លុបទំនិញបានជោគជ័យ!');
    }
}
