@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $transaction->receipt_number }}</h5>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Patient:</strong> {{ $transaction->patient->full_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Amount Paid:</strong> <span class="text-success font-weight-bold">${{ number_format($transaction->amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Payment Method:</strong> {{ ucfirst($transaction->payment_method) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Status:</strong>
                            <span class="badge bg-{{ $transaction->payment_status == 'completed' ? 'success' : ($transaction->payment_status == 'refunded' ? 'danger' : 'warning') }}">
                                {{ ucfirst($transaction->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Remaining Balance:</strong> <span class="text-danger font-weight-bold">${{ number_format($transaction->remaining_balance, 2) }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Transaction Date:</strong> {{ $transaction->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Appointment:</strong>
                        <a href="{{ route('appointments.show', $transaction->appointment) }}">
                            {{ $transaction->appointment->patient->full_name }} with {{ $transaction->appointment->doctor->full_name }}
                        </a>
                    </div>

                    @if ($transaction->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $transaction->description }}</p>
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        @if ($transaction->payment_status != 'refunded')
                        <form method="POST" action="{{ route('transactions.refund', $transaction) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-danger" onclick="return confirm('Process refund?')">
                                <i class="bi bi-arrow-counterclockwise"></i> Process Refund
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection