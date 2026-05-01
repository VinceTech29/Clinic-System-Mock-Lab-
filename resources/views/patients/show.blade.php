@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $patient->full_name }}</h5>
                    <div>
                        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $patient->email }}
                        </div>
                        <div class="col-md-6">
                            <strong>Phone:</strong> {{ $patient->phone }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Date of Birth:</strong> {{ $patient->date_of_birth->format('M d, Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Gender:</strong> {{ $patient->gender }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Address:</strong> {{ $patient->address ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Medical History:</strong> {{ $patient->medical_history ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Emergency Contact:</strong> {{ $patient->emergency_contact ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($patient->appointments->count())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Appointments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Doctor</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patient->appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->doctor->full_name }}</td>
                                    <td>{{ $appointment->appointment_date_time->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($appointment->consultation_fee, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
