@extends('layouts.admin')

@section('content')
    <h1>Pending Professionals</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Business Name</th>
                <th>Contact Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($professionals as $professional)
                <tr>
                    <td>{{ $professional->business_name }}</td>
                    <td>{{ $professional->user->name }}</td>
                    <td>{{ $professional->user->email }}</td>
                    <td>{{ $professional->approval_status }}</td>
                    <td>
                        <a href="{{ route('admin.professionals.show', $professional) }}" class="btn btn-primary btn-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No pending professionals found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
