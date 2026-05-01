@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add Doctor
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($doctors->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Specialization</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Consultation Fee</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->full_name }}</td>
                                <td>{{ $doctor->specialization }}</td>
                                <td>{{ $doctor->email }}</td>
                                <td>{{ $doctor->phone }}</td>
                                <td>${{ number_format($doctor->consultation_fee, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $doctor->status == 'available' ? 'success' : 'danger' }}">
                                        {{ ucfirst($doctor->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('doctors.destroy', $doctor) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center">No doctors found. <a href="{{ route('doctors.create') }}">Add one now!</a></p>
            @endif
        </div>
    </div>
</div>
@endsection