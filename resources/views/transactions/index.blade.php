@extends('layouts.app')

@section('title', 'Billing & Payments')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Record Payment
            </a>
            <a href="{{ route('transactions.report') }}" class="btn btn-info">
                <i class="bi bi-graph-up"></i> Revenue Report
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($transactions->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Receipt #</th>
                                <th>Patient</th>
                                <th>Appointment</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Balance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->receipt_number }}</td>
                                <td>{{ $transaction->patient->full_name }}</td>
                                <td>{{ $transaction->appointment->appointment_date_time->format('M d, Y') }}</td>
                                <td>${{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ ucfirst($transaction->payment_method) }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->payment_status == 'completed' ? 'success' : ($transaction->payment_status == 'refunded' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($transaction->payment_status) }}
                                    </span>
                                </td>
                                <td>${{ number_format($transaction->remaining_balance, 2) }}</td>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($transaction->payment_status != 'refunded')
                                    <form method="POST" action="{{ route('transactions.refund', $transaction) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Process refund?')">
                                            <i class="bi bi-arrow-counterclockwise"></i>
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
                <p class="text-muted text-center">No payments recorded yet. <a href="{{ route('transactions.create') }}">Record one now!</a></p>
            @endif
        </div>
    </div>
</div>
@endsection