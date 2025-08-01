@extends('layouts.admin')

@section('content')
    <h1>{{ $professional->business_name }}</h1>

    <p><strong>Contact Name:</strong> {{ $professional->user->name }}</p>
    <p><strong>Email:</strong> {{ $professional->user->email }}</p>
    <p><strong>Services:</strong> {{ $professional->services }}</p>
    <p><strong>Phone Number:</strong> {{ $professional->phone_number }}</p>
    <p><strong>Website:</strong> <a href="{{ $professional->website }}" target="_blank">{{ $professional->website }}</a></p>
    <p><strong>Country:</strong> {{ $professional->country }}</p>
    <p><strong>City:</strong> {{ $professional->city }}</p>

    <form action="{{ route('admin.professionals.approve', $professional) }}" method="POST" style="display: inline-block;">
        @csrf
        <button type="submit" class="btn btn-success">Approve</button>
    </form>

    <form action="{{ route('admin.professionals.reject', $professional) }}" method="POST" style="display: inline-block;">
        @csrf
        <button type="submit" class="btn btn-danger">Reject</button>
    </form>
@endsection
