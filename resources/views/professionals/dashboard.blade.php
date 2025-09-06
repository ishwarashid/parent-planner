<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Professional Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Profile</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="mb-2"><strong>Business Name:</strong> {{ $professional->business_name }}</p>
                            <p class="mb-2"><strong>Phone Number:</strong> {{ $professional->phone_number }}</p>

                            <!-- FIX START: Replaced the direct echo with a loop -->
                            <div>
                                <p><strong>Services:</strong></p>
                                @if ($professional->services && count($professional->services) > 0)
                                    <ul class="list-disc list-inside ml-4 mt-1 text-sm text-gray-700">
                                        @foreach ($professional->services as $service)
                                            <li>{{ $service }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-500">No services listed.</p>
                                @endif
                            </div>
                            <!-- FIX END -->

                        </div>
                        <div>
                            <p class="mb-2"><strong>Website:</strong>
                                @if ($professional->website)
                                    <a href="{{ $professional->website }}" class="text-blue-500 hover:underline"
                                        target="_blank" rel="noopener noreferrer">{{ $professional->website }}</a>
                                @else
                                    <span class="text-gray-500">Not provided</span>
                                @endif
                            </p>

                            <p class="mb-2">
                                <strong>Facebook:</strong>
                                @if ($professional->facebook)
                                    <a href="{{ $professional->facebook }}" class="text-blue-500 hover:underline"
                                        target="_blank" rel="noopener noreferrer">{{ $professional->facebook }}</a>
                                @else
                                    <span class="text-gray-500">Not provided</span>
                                @endif
                            </p>

                            <p class="mb-2">
                                <strong>Linkedin:</strong>
                                @if ($professional->linkedin)
                                    <a href="{{ $professional->linkedin }}" class="text-blue-500 hover:underline"
                                        target="_blank" rel="noopener noreferrer">{{ $professional->linkedin }}</a>
                                @else
                                    <span class="text-gray-500">Not provided</span>
                                @endif
                            </p>

                            <p class="mb-2">
                                <strong>Linkedin:</strong>
                                @if ($professional->linkedin)
                                    <a href="{{ $professional->linkedin }}" class="text-blue-500 hover:underline"
                                        target="_blank" rel="noopener noreferrer">{{ $professional->linkedin }}</a>
                                @else
                                    <span class="text-gray-500">Not provided</span>
                                @endif
                            </p>


                            <p class="mb-2"><strong>Location:</strong>
                                @if ($professional->city || $professional->country)
                                    {{ $professional->city }}{{ $professional->city && $professional->country ? ',' : '' }}
                                    {{ $professional->country }}
                                @else
                                    <span class="text-gray-500">Not provided</span>
                                @endif
                            </p>
                            <p><strong>Approval Status:</strong> <span
                                    class="font-bold {{ $professional->approval_status === 'approved' ? 'text-green-600' : ($professional->approval_status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">{{ ucfirst($professional->approval_status) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('professional.professional.profile.edit') }}"
                            class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Subscription</h3>
                    @if ($professional->user->subscribed('default'))
                        <p>You are currently subscribed.</p>
                        <a href="{{ route('subscription.show') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">
                            Manage Subscription
                        </a>
                    @else
                        <p>You are not subscribed. Please subscribe to be listed on the platform.</p>
                        <a href="{{ route('professional.pricing') }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">
                            Subscribe Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
