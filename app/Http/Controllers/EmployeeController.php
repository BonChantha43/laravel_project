<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index() {
        $employees = Employee::latest()->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        $data = $request->all();
        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('employees', 'public');
        }
        Employee::create($data);
        return back()->with('success', 'បុគ្គលិកត្រូវបានបង្កើតជោគជ័យ!');
    }

    public function update(Request $request, $id) {
        $employee = Employee::find($id);
        $data = $request->all();
        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('employees', 'public');
        }
        $employee->update($data);
        return back()->with('success', 'ទិន្នន័យត្រូវបានកែប្រែ!');
    }

    public function destroy($id) {
        Employee::destroy($id);
        return back()->with('success', 'បុគ្គលិកត្រូវបានលុប!');
    }
}