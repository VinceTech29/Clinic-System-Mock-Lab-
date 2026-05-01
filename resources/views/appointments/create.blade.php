@extends('layouts.app')

@section('title', 'Schedule Appointment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Schedule New Appointment</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="patient_id" class="form-label">Select Patient</label>
                                <select class="form-control @error('patient_id') is-invalid @enderror"
                                        id="patient_id" name="patient_id" required>
                                    <option value="">-- Choose Patient --</option>
                                    @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }} ({{ $patient->email }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                <select class="form-control @error('doctor_id') is-invalid @enderror"
                                        id="doctor_id" name="doctor_id" required>
                                    <option value="">-- Choose Doctor --</option>
                                    @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->full_name }} - {{ $doctor->specialization }} (${{ number_format($doctor->consultation_fee, 2) }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_date_time" class="form-label">Appointment Date & Time</label>
                            <input type="datetime-local" class="form-control @error('appointment_date_time') is-invalid @enderror"
                                   id="appointment_date_time" name="appointment_date_time" value="{{ old('appointment_date_time') }}" required>
                            <small class="form-text text-muted">Select a future date and time. Appointments are in 30-minute slots.</small>
                            @error('appointment_date_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason_for_visit" class="form-label">Reason for Visit</label>
                            <input type="text" class="form-control @error('reason_for_visit') is-invalid @enderror"
                                   id="reason_for_visit" name="reason_for_visit" value="{{ old('reason_for_visit') }}" required>
                            @error('reason_for_visit')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> The system will automatically check for conflicts and prevent overlapping appointments for the same doctor.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Schedule Appointment</button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection