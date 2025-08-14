@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Admin Dashboard</h1>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-semibold text-gray-800">Welcome to the Admin Panel</h3>
            <p class="mt-2 text-gray-600">From here you can manage different aspects of the application. Use the navigation links to get started.</p>
            <div class="mt-6">
                <a href="{{ route('admin.professionals.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Manage Professionals
                </a>
            </div>
        </div>
    </div>
@endsection
