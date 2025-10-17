<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent to you. If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif

    @if (session('status') == 'verification-link-sent' && session('message'))
        <div class="mb-4 font-medium text-sm text-blue-600">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif

    @php
        $verificationAttempts = auth()->user()->verificationAttempts()->latest()->limit(5)->get();
        $latestAttempt = $verificationAttempts->first();
        $failedAttempts = $verificationAttempts->where('status', 'failed');
    @endphp

    @if($latestAttempt && $failedAttempts->count() > 0)
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
            <p class="text-sm text-yellow-800">
                <strong>Verification Status:</strong> 
                @if($failedAttempts->count() == 1)
                    We had difficulty delivering your verification email. A new one has been sent.
                @elseif($failedAttempts->count() > 1)
                    We've sent {{ $failedAttempts->count() }} verification emails, but couldn't confirm delivery. Please check your spam folder or request a new one.
                @endif
            </p>
            @if($latestAttempt->error_message)
                <p class="mt-2 text-sm text-yellow-700">
                    <strong>Recent issue:</strong> {{ $latestAttempt->error_message }}
                </p>
            @endif
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <div class="text-sm text-gray-600">
            <p>Need help? Check your spam folder or try using a different email address.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
