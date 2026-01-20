<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // ១. បង្ហាញបញ្ជីប្រភេទ
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    // ២. បង្កើតប្រភេទថ្មី
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);
        
        Category::create([
            'name' => $request->name,
            'description' => $request->description // បើមាន field នេះក្នុង database
        ]);

        return back()->with('success', 'បង្កើតប្រភេទថ្មីជោគជ័យ!');
    }

    // ៣. កែប្រែ (Update)
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return back()->with('success', 'កែប្រែជោគជ័យ!');
    }

    // ៤. លុប (Delete)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return back()->with('success', 'លុបជោគជ័យ!');
    }
}