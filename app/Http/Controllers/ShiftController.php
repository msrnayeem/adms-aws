<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();

        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'entry_time' => 'required|date_format:H:i',
            'late_entry' => 'nullable|integer|min:0',
            'out_time' => 'required|date_format:H:i',
            'early_out_time' => 'nullable|integer|min:0',
        ]);

        // Default to false for unchecked days & set default values
        $validated = array_merge([
            'saturday' => $request->has('saturday'),
            'sunday' => $request->has('sunday'),
            'monday' => $request->has('monday'),
            'tuesday' => $request->has('tuesday'),
            'wednesday' => $request->has('wednesday'),
            'thursday' => $request->has('thursday'),
            'friday' => $request->has('friday'),
            'late_entry' => $request->input('late_entry', 0), // Default 0 if null
            'early_out_time' => $request->input('early_out_time', 0), // Default 0 if null
        ], $validated);

        Shift::create($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift created successfully.');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'saturday' => 'boolean',
            'sunday' => 'boolean',
            'monday' => 'boolean',
            'tuesday' => 'boolean',
            'wednesday' => 'boolean',
            'thursday' => 'boolean',
            'friday' => 'boolean',
            'entry_time' => 'required|date_format:H:i',
            'late_entry' => 'nullable|integer|min:0',
            'out_time' => 'required|date_format:H:i',
            'early_out_time' => 'nullable|integer|min:0',
        ]);

        // Merge the checkbox values for active days
        $validated = array_merge([
            'saturday' => $request->has('saturday'),
            'sunday' => $request->has('sunday'),
            'monday' => $request->has('monday'),
            'tuesday' => $request->has('tuesday'),
            'wednesday' => $request->has('wednesday'),
            'thursday' => $request->has('thursday'),
            'friday' => $request->has('friday'),
        ], $validated);

        // Update the shift
        $shift->update($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift updated successfully.');
    }

}
