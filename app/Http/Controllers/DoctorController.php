<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // Display all doctors
    public function index()
    {
        $doctors = Doctor::all();
        return view('doctors.index', compact('doctors'));
    }

    // Show create form
    public function create()
    {
        return view('doctors.create');
    }

    // Store doctor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:doctors',
            'phone' => 'required|string|max:15',
            'specialization' => 'required|string|max:100',
            'consultation_fee' => 'required|numeric|min:0',
            'license_number' => 'required|string|unique:doctors',
            'bio' => 'nullable|string',
            'status' => 'required|in:available,unavailable',
        ]);

        Doctor::create($validated);
        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully!');
    }

    // Show doctor details
    public function show(Doctor $doctor)
    {
        $appointments = $doctor->appointments()->with('patient')->latest()->get();
        return view('doctors.show', compact('doctor', 'appointments'));
    }

    // Show edit form
    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    // Update doctor
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'required|string|max:15',
            'specialization' => 'required|string|max:100',
            'consultation_fee' => 'required|numeric|min:0',
            'license_number' => 'required|string|unique:doctors,license_number,' . $doctor->id,
            'bio' => 'nullable|string',
            'status' => 'required|in:available,unavailable',
        ]);

        $doctor->update($validated);
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully!');
    }

    // Delete doctor
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully!');
    }
}
