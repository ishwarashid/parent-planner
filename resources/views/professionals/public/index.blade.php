<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Professionals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('professionals.public.index') }}" method="GET" class="mb-6">
                        <div class="flex items-center">
                            <x-text-input type="text" name="search" class="w-full" placeholder="Search by name, service, location..." value="{{ request('search') }}" />
                            <x-primary-button class="ms-3">
                                {{ __('Search') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($professionals as $professional)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $professional->business_name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $professional->city }}, {{ $professional->country }}</p>
                                    <p class="text-gray-700 mt-4">{{ Str::limit($professional->services, 100) }}</p>
                                    @if($professional->website)
                                        <div class="mt-4">
                                            <a href="{{ $professional->website }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" target="_blank" rel="noopener noreferrer">
                                                Visit Website
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500">
                                <p>No professionals found.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $professionals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
