@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Appointment Details</h5>
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Patient:</strong> {{ $appointment->patient->full_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Doctor:</strong> {{ $appointment->doctor->full_name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Date & Time:</strong> {{ $appointment->appointment_date_time->format('M d, Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : ($appointment->status == 'confirmed' ? 'info' : 'warning')) }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Consultation Fee:</strong> ${{ number_format($appointment->consultation_fee, 2) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Reason for Visit:</strong> {{ $appointment->reason_for_visit }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Notes:</strong>
                        <p class="text-muted">{{ $appointment->notes ?? 'N/A' }}</p>
                    </div>

                    <div class="d-flex gap-2">
                        @if ($appointment->canReschedule())
                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-repeat"></i> Reschedule
                        </a>
                        @endif
                        @if ($appointment->canBeCancelled())
                        <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Cancel this appointment?')">
                                <i class="bi bi-x"></i> Cancel
                            </button>
                        </form>
                        @endif
                        @if ($appointment->status == 'pending')
                        <form method="POST" action="{{ route('appointments.confirm', $appointment) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success btn-sm">
                                <i class="bi bi-check"></i> Confirm
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            @if ($appointment->transaction)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Receipt #:</strong> {{ $appointment->transaction->receipt_number }}
                        </div>
                        <div class="col-md-6">
                            <strong>Amount Paid:</strong> ${{ number_format($appointment->transaction->amount, 2) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Payment Method:</strong> {{ ucfirst($appointment->transaction->payment_method) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Status:</strong>
                            <span class="badge bg-{{ $appointment->transaction->payment_status == 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($appointment->transaction->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection