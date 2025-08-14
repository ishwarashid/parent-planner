@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">{{ $professional->business_name }}</h1>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Contact Information</h3>
                    <p class="mt-2"><strong>Contact Name:</strong> {{ $professional->user->name }}</p>
                    <p><strong>Email:</strong> {{ $professional->user->email }}</p>
                    <p><strong>Phone Number:</strong> {{ $professional->phone_number }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Business Details</h3>
                    <p class="mt-2">
                        <strong>Location:</strong>
                        {{-- Build the location string conditionally to avoid extra commas --}}
                        @php
                            $locationParts = array_filter([
                                $professional->city,
                                $professional->country,
                                $professional->continent,
                            ]);
                            echo implode(', ', $locationParts);
                        @endphp
                        @if (empty($locationParts))
                            <span class="text-gray-500">Not provided</span>
                        @endif
                    </p>
                    <p>
                        <strong>Website:</strong>
                        @if ($professional->website)
                            <a href="{{ $professional->website }}" class="text-blue-500 hover:underline" target="_blank"
                                rel="noopener noreferrer">{{ $professional->website }}</a>
                        @else
                            <span class="text-gray-500">Not provided</span>
                        @endif
                    </p>

                    <p>
                        <strong>Facebook:</strong>
                        @if ($professional->facebook)
                            <a href="{{ $professional->facebook }}" class="text-blue-500 hover:underline" target="_blank"
                                rel="noopener noreferrer">{{ $professional->facebook }}</a>
                        @else
                            <span class="text-gray-500">Not provided</span>
                        @endif
                    </p>

                    <p>
                        <strong>Linkedin:</strong>
                        @if ($professional->linkedin)
                            <a href="{{ $professional->linkedin }}" class="text-blue-500 hover:underline" target="_blank"
                                rel="noopener noreferrer">{{ $professional->linkedin }}</a>
                        @else
                            <span class="text-gray-500">Not provided</span>
                        @endif
                    </p>

                    <p>
                        <strong>Linkedin:</strong>
                        @if ($professional->linkedin)
                            <a href="{{ $professional->linkedin }}" class="text-blue-500 hover:underline" target="_blank"
                                rel="noopener noreferrer">{{ $professional->linkedin }}</a>
                        @else
                            <span class="text-gray-500">Not provided</span>
                        @endif
                    </p>

                    <p>
                        <strong>Instagram:</strong>
                        @if ($professional->instagram)
                            <a href="{{ $professional->instagram }}" class="text-blue-500 hover:underline" target="_blank"
                                rel="noopener noreferrer">{{ $professional->instagram }}</a>
                        @else
                            <span class="text-gray-500">Not provided</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- FIX START: Services Display -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-800">Services</h3>
                @if ($professional->services && count($professional->services) > 0)
                    <ul class="list-disc list-inside ml-4 mt-2 space-y-1 text-sm text-gray-700">
                        @foreach ($professional->services as $service)
                            <li>{{ $service }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-2 text-gray-500">No services listed.</p>
                @endif
            </div>
            <!-- FIX END -->

            <div class="mt-8 border-t pt-6 flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-800">Actions</h3>

                @if ($professional->approval_status !== 'approved')
                    <form action="{{ route('admin.professionals.approve', $professional) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Approve
                        </button>
                    </form>
                @endif

                @if ($professional->approval_status !== 'rejected')
                    <form action="{{ route('admin.professionals.reject', $professional) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Reject
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
