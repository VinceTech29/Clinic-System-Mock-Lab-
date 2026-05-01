@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <h6>Today's Appointments</h6>
                <h3>{{ $todayAppointments }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>Total Patients</h6>
                <h3>{{ $totalPatients }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>Total Doctors</h6>
                <h3>{{ $totalDoctors }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>Today's Revenue</h6>
                <h3>${{ number_format($todayRevenue, 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Appointments -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Appointments</h5>
                    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="card-body">
                    @if ($recentAppointments->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->patient->full_name }}</td>
                                        <td>{{ $appointment->doctor->full_name }}</td>
                                        <td>{{ $appointment->appointment_date_time->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No recent appointments</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Appointments</h5>
                    <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-light">New Appointment</a>
                </div>
                <div class="card-body">
                    @if ($upcomingAppointments->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Scheduled</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upcomingAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->patient->full_name }}</td>
                                        <td>{{ $appointment->doctor->full_name }}</td>
                                        <td>{{ $appointment->appointment_date_time->format('M d, Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No upcoming appointments</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
