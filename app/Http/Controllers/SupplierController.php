<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::latest()->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        Supplier::create($request->all());
        return back()->with('success', 'អ្នកផ្គត់ផ្គង់ត្រូវបានបង្កើត!');
    }

    public function update(Request $request, $id) {
        Supplier::find($id)->update($request->all());
        return back()->with('success', 'ទិន្នន័យត្រូវបានកែប្រែ!');
    }

    public function destroy($id) {
        Supplier::destroy($id);
        return back()->with('success', 'ទិន្នន័យត្រូវបានលុប!');
    }
}