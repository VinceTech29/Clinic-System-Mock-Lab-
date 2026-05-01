@extends('layouts.app')

@section('title', 'Reschedule Appointment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Reschedule Appointment</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="patient" class="form-label">Patient</label>
                            <input type="text" class="form-control" value="{{ $appointment->patient->full_name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="doctor" class="form-label">Doctor</label>
                            <input type="text" class="form-control" value="{{ $appointment->doctor->full_name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_date_time" class="form-label">New Date & Time</label>
                            <input type="datetime-local" class="form-control @error('appointment_date_time') is-invalid @enderror"
                                   id="appointment_date_time" name="appointment_date_time"
                                   value="{{ old('appointment_date_time', $appointment->appointment_date_time->format('Y-m-d\\TH:i')) }}" required>
                            @error('appointment_date_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> The system will check for conflicts with the new time slot.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Reschedule Appointment</button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection