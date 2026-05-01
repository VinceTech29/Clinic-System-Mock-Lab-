@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Schedule Appointment
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($appointments->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date & Time</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Fee</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->full_name }}</td>
                                <td>{{ $appointment->doctor->full_name }}</td>
                                <td>{{ $appointment->appointment_date_time->format('M d, Y H:i') }}</td>
                                <td>{{ $appointment->reason_for_visit }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : ($appointment->status == 'confirmed' ? 'info' : 'warning')) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>${{ number_format($appointment->consultation_fee, 2) }}</td>
                                <td>
                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($appointment->canReschedule())
                                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </a>
                                    @endif
                                    @if ($appointment->canBeCancelled())
                                    <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if ($appointment->status == 'pending')
                                    <form method="POST" action="{{ route('appointments.confirm', $appointment) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">No appointments found. <a href="{{ route('appointments.create') }}">Schedule one now!</a></p>
            @endif
        </div>
    </div>
</div>
@endsection