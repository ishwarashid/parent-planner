<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Professional Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Profile</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>Business Name:</strong> {{ $professional->business_name }}</p>
                            <p><strong>Services:</strong> {{ $professional->services }}</p>
                            <p><strong>Phone Number:</strong> {{ $professional->phone_number }}</p>
                        </div>
                        <div>
                            <p><strong>Website:</strong> <a href="{{ $professional->website }}" class="text-blue-500 hover:underline" target="_blank">{{ $professional->website }}</a></p>
                            <p><strong>Location:</strong> {{ $professional->city }}, {{ $professional->country }}</p>
                            <p><strong>Approval Status:</strong> <span class="font-bold {{ $professional->approval_status === 'approved' ? 'text-green-600' : ($professional->approval_status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">{{ ucfirst($professional->approval_status) }}</span></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('professional.profile.edit') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Subscription</h3>
                    @if($professional->user->subscribed('default'))
                        <p>You are currently subscribed.</p>
                        <a href="{{ route('billing.portal') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">
                            Manage Subscription
                        </a>
                    @else
                        <p>You are not subscribed. Please subscribe to be listed on the platform.</p>
                        <a href="{{ route('pricing') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">
                            Subscribe Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
