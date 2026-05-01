@extends('layouts.app')

@section('title', 'Revenue Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Total Revenue</h6>
                <h3 class="text-success">${{ number_format($totalRevenue, 2) }}</h3>
                <small class="text-muted">Completed Payments</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Pending Payments</h6>
                <h3 class="text-warning">${{ number_format($pendingPayments, 2) }}</h3>
                <small class="text-muted">Outstanding Balances</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Total Transactions</h6>
                <h3 class="text-info">{{ $totalTransactions }}</h3>
                <small class="text-muted">All Payment Records</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Revenue by Doctor</h5>
        </div>
        <div class="card-body">
            @if ($revenueByDoctor->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Doctor Name</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($revenueByDoctor as $revenue)
                            <tr>
                                <td>{{ $revenue->first_name }} {{ $revenue->last_name }}</td>
                                <td><span class="badge bg-success">${{ number_format($revenue->total, 2) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">No revenue data available yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection