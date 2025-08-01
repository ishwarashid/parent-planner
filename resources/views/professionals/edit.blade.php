<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('professional.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Business Name -->
                            <div>
                                <x-input-label for="business_name" :value="__('Business Name')" />
                                <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name', $professional->business_name)" required />
                                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <x-input-label for="phone_number" :value="__('Phone Number')" />
                                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $professional->phone_number)" required />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>

                            <!-- Country -->
                            <div>
                                <x-input-label for="country" :value="__('Country')" />
                                <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country', $professional->country)" required />
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>

                            <!-- City -->
                            <div>
                                <x-input-label for="city" :value="__('City')" />
                                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $professional->city)" required />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="mt-6">
                            <x-input-label for="services" :value="__('Services')" />
                            <textarea id="services" name="services" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('services', $professional->services) }}</textarea>
                            <x-input-error :messages="$errors->get('services')" class="mt-2" />
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Social Media (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Website -->
                            <div>
                                <x-input-label for="website" :value="__('Website')" />
                                <x-text-input id="website" class="block mt-1 w-full" type="url" name="website" :value="old('website', $professional->website)" />
                                <x-input-error :messages="$errors->get('website')" class="mt-2" />
                            </div>

                            <!-- Facebook -->
                            <div>
                                <x-input-label for="facebook" :value="__('Facebook')" />
                                <x-text-input id="facebook" class="block mt-1 w-full" type="url" name="facebook" :value="old('facebook', $professional->facebook)" />
                                <x-input-error :messages="$errors->get('facebook')" class="mt-2" />
                            </div>

                            <!-- LinkedIn -->
                            <div>
                                <x-input-label for="linkedin" :value="__('LinkedIn')" />
                                <x-text-input id="linkedin" class="block mt-1 w-full" type="url" name="linkedin" :value="old('linkedin', $professional->linkedin)" />
                                <x-input-error :messages="$errors->get('linkedin')" class="mt-2" />
                            </div>

                            <!-- Twitter -->
                            <div>
                                <x-input-label for="twitter" :value="__('Twitter')" />
                                <x-text-input id="twitter" class="block mt-1 w-full" type="url" name="twitter" :value="old('twitter', $professional->twitter)" />
                                <x-input-error :messages="$errors->get('twitter')" class="mt-2" />
                            </div>

                            <!-- Instagram -->
                            <div>
                                <x-input-label for="instagram" :value="__('Instagram')" />
                                <x-text-input id="instagram" class="block mt-1 w-full" type="url" name="instagram" :value="old('instagram', $professional->instagram)" />
                                <x-input-error :messages="$errors->get('instagram')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Update Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
