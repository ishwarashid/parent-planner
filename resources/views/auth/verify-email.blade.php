<x-guest-layout>
    <div class="mb-6 p-6 bg-white rounded-lg shadow-sm border border-gray-100">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">{{ __('Email Verification Required') }}</h2>
        <div class="text-gray-600 leading-relaxed">
            {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent to you. If you didn\'t receive the email, we will gladly send you another.') }}
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start">
            <svg class="h-5 w-5 text-green-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-green-700">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        </div>
    @endif

    @if (session('status') == 'verification-link-sent' && session('message'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start">
            <svg class="h-5 w-5 text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-700">
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start">
            <svg class="h-5 w-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-red-700">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @php
        $verificationAttempts = auth()->user()->verificationAttempts()->latest()->limit(5)->get();
        $latestAttempt = $verificationAttempts->first();
        $failedAttempts = $verificationAttempts->where('status', 'failed');
    @endphp

    @if($latestAttempt && $failedAttempts->count() > 0)
        <div class="mb-6 p-5 bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-yellow-800 mb-1">Verification Status</h3>
                    <p class="text-sm text-yellow-700">
                        @if($failedAttempts->count() == 1)
                            We had difficulty delivering your verification email. A new one has been sent.
                        @elseif($failedAttempts->count() > 1)
                            We've sent {{ $failedAttempts->count() }} verification emails, but couldn't confirm delivery. Please check your spam folder or request a new one.
                        @endif
                    </p>
                    @if($latestAttempt->error_message)
                        <div class="mt-3 p-3 bg-yellow-100 rounded-md">
                            <p class="text-sm text-yellow-800">
                                <span class="font-medium">Recent issue:</span> {{ $latestAttempt->error_message }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="mt-8 space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            {{-- Resend Button --}}
            <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                @csrf
                <x-primary-button class="w-full sm:w-auto flex items-center justify-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>

        {{-- Help Text --}}
        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Need help?</span>
                    <p class="mt-1">Check your spam folder or try using a different email address. If you continue to experience issues, please contact support.</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
