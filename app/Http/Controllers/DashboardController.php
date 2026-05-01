<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Main dashboard
    public function index()
    {
        // Today's statistics
        $todayAppointments = Appointment::whereDate('appointment_date_time', today())->count();
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $todayRevenue = Transaction::where('payment_status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');

        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->limit(5)
            ->get();

        // Upcoming appointments
        $upcomingAppointments = Appointment::where('appointment_date_time', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date_time')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'todayAppointments',
            'totalPatients',
            'totalDoctors',
            'todayRevenue',
            'recentAppointments',
            'upcomingAppointments'
        ));
    }
}
