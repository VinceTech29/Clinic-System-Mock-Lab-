@extends('layouts.app')

@section('title', 'Doctor Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $doctor->full_name }}</h5>
                    <div>
                        <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('doctors.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Specialization:</strong> {{ $doctor->specialization }}
                        </div>
                        <div class="col-md-6">
                            <strong>License #:</strong> {{ $doctor->license_number }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $doctor->email }}
                        </div>
                        <div class="col-md-6">
                            <strong>Phone:</strong> {{ $doctor->phone }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Consultation Fee:</strong> ${{ number_format($doctor->consultation_fee, 2) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $doctor->status == 'available' ? 'success' : 'danger' }}">
                                {{ ucfirst($doctor->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Bio:</strong> {{ $doctor->bio ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($appointments->count())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Scheduled Appointments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Date & Time</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->full_name }}</td>
                                    <td>{{ $appointment->appointment_date_time->format('M d, Y H:i') }}</td>
                                    <td>{{ $appointment->reason_for_visit }}</td>
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
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
