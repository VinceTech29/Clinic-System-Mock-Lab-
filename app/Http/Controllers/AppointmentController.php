<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // Display all appointments
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->latest()->get();
        return view('appointments.index', compact('appointments'));
    }

    // Show create form
    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    // Store appointment with conflict checking
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date_time' => 'required|date|after:now',
            'reason_for_visit' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Check for overlapping appointments for the doctor
        $appointmentDateTime = Carbon::parse($validated['appointment_date_time']);
        $exists = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('status', '!=', 'cancelled')
            ->whereBetween('appointment_date_time', [
                $appointmentDateTime->copy()->subMinutes(30),
                $appointmentDateTime->copy()->addMinutes(30)
            ])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Doctor is not available at this time. Please choose another time slot.');
        }

        // Get doctor's consultation fee
        $doctor = Doctor::find($validated['doctor_id']);
        $validated['consultation_fee'] = $doctor->consultation_fee;
        $validated['status'] = 'pending';

        Appointment::create($validated);
        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully!');
    }

    // Show appointment details
    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    // Show edit form for rescheduling
    public function edit(Appointment $appointment)
    {
        if (!$appointment->canReschedule()) {
            return back()->with('error', 'This appointment cannot be rescheduled.');
        }
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    // Update (reschedule) appointment
    public function update(Request $request, Appointment $appointment)
    {
        if (!$appointment->canReschedule()) {
            return back()->with('error', 'This appointment cannot be rescheduled.');
        }

        $validated = $request->validate([
            'appointment_date_time' => 'required|date|after:now',
        ]);

        // Check for conflicts with new time
        $appointmentDateTime = Carbon::parse($validated['appointment_date_time']);
        $exists = Appointment::where('doctor_id', $appointment->doctor_id)
            ->where('id', '!=', $appointment->id)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('appointment_date_time', [
                $appointmentDateTime->copy()->subMinutes(30),
                $appointmentDateTime->copy()->addMinutes(30)
            ])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Doctor is not available at this new time.');
        }

        $appointment->update($validated);
        return redirect()->route('appointments.index')->with('success', 'Appointment rescheduled successfully!');
    }

    // Cancel appointment
    public function cancel(Appointment $appointment)
    {
        if (!$appointment->canBeCancelled()) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);
        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled successfully!');
    }

    // Confirm appointment
    public function confirm(Appointment $appointment)
    {
        $appointment->update(['status' => 'confirmed']);
        return redirect()->route('appointments.index')->with('success', 'Appointment confirmed successfully!');
    }
}
