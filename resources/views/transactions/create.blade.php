@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Record New Payment</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="appointment_id" class="form-label">Select Completed Appointment</label>
                            <select class="form-control @error('appointment_id') is-invalid @enderror"
                                    id="appointment_id" name="appointment_id" required onchange="updateFee()">
                                <option value="">-- Choose Appointment --</option>
                                @foreach ($appointments as $appointment)
                                <option value="{{ $appointment->id }}" data-fee="{{ $appointment->consultation_fee }}"
                                        {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                    {{ $appointment->patient->full_name }} - {{ $appointment->doctor->full_name }} ({{ $appointment->appointment_date_time->format('M d, Y') }}) - ${{ number_format($appointment->consultation_fee, 2) }}
                                </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount Paid ($)</label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                   id="amount" name="amount" value="{{ old('amount') }}"
                                   step="0.01" min="0.01" required>
                            <small class="form-text text-muted">Enter the amount patient is paying (full or partial).</small>
                            @error('amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-control @error('payment_method') is-invalid @enderror"
                                    id="payment_method" name="payment_method" required>
                                <option value="">-- Choose Method --</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            @error('payment_method')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="2">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> A receipt will be automatically generated for this payment.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Record Payment</button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFee() {
    const select = document.getElementById('appointment_id');
    const selected = select.options[select.selectedIndex];
    const fee = selected.getAttribute('data-fee');
}
</script>
@endsection