<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::all();

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shifts = Shift::all();

        return view('employees.create', compact('shifts'));
    }

    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'zk_device_id' => 'nullable|integer', 
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'zk_device_id' => $validated['zk_device_id'], 
            'shift_id' => $validated['shift_id'] ?? null,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }


    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $shifts = Shift::all();
       
        return view('employees.edit', compact('employee', 'shifts'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'zk_device_id' => 'nullable|integer',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

}
