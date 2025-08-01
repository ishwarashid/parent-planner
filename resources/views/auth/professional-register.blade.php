<x-guest-layout>
    <form method="POST" action="{{ route('professional.register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Business Name -->
        <div class="mt-4">
            <x-input-label for="business_name" :value="__('Business Name')" />
            <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" required />
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <!-- Services -->
        <div class="mt-4">
            <x-input-label for="services" :value="__('Services')" />
            <textarea id="services" name="services" class="block mt-1 w-full" required>{{ old('services') }}</textarea>
            <x-input-error :messages="$errors->get('services')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" required />
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <!-- City -->
        <div class="mt-4">
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <!-- Website -->
        <div class="mt-4">
            <x-input-label for="website" :value="__('Website (Optional)')" />
            <x-text-input id="website" class="block mt-1 w-full" type="url" name="website" :value="old('website')" />
            <x-input-error :messages="$errors->get('website')" class="mt-2" />
        </div>

        <!-- Social Media -->
        <div class="mt-4">
            <x-input-label for="facebook" :value="__('Facebook (Optional)')" />
            <x-text-input id="facebook" class="block mt-1 w-full" type="url" name="facebook" :value="old('facebook')" />
            <x-input-error :messages="$errors->get('facebook')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="linkedin" :value="__('LinkedIn (Optional)')" />
            <x-text-input id="linkedin" class="block mt-1 w-full" type="url" name="linkedin" :value="old('linkedin')" />
            <x-input-error :messages="$errors->get('linkedin')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="twitter" :value="__('Twitter (Optional)')" />
            <x-text-input id="twitter" class="block mt-1 w-full" type="url" name="twitter" :value="old('twitter')" />
            <x-input-error :messages="$errors->get('twitter')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="instagram" :value="__('Instagram (Optional)')" />
            <x-text-input id="instagram" class="block mt-1 w-full" type="url" name="instagram" :value="old('instagram')" />
            <x-input-error :messages="$errors->get('instagram')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
