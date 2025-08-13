<x-guest-layout>
    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }
    </style>

    <form id="multiStepForm" method="POST" action="{{ route('professional.register') }}">
        @csrf

        <!-- Step 1: Account Information -->
        <div id="step1" class="step active">
            <h2 class="text-lg font-medium text-black mb-4">Step 1: Account Information</h2>
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Step 2: Business Information -->
        <div id="step2" class="step">
            <h2 class="text-lg font-medium text-black mb-4">Step 2: Business Information</h2>
            <!-- Business Name -->
            <div>
                <x-input-label for="business_name" :value="__('Business Name')" />
                <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name"
                    :value="old('business_name')" required />
                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
            </div>

            <!-- Services -->
            <div class="mt-4" id="services-container">
                <x-input-label :value="__('Services (Select at least one)')" />
                <div class="mt-2 space-y-2">
                    @foreach ($services as $service)
                        <label for="service_{{ $loop->index }}" class="flex items-center">
                            <input type="checkbox" id="service_{{ $loop->index }}" name="services[]"
                                value="{{ $service }}"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                {{ is_array(old('services')) && in_array($service, old('services')) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">{{ $service }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('services')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div class="mt-4">
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number"
                    :value="old('phone_number')" required />
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <!-- Continent -->
            <div class="mt-4">
                <x-input-label for="continent" :value="__('Continent')" />
                <select name="continent" id="continent"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select a Continent</option>
                    @foreach ($continents as $continent)
                        <option value="{{ $continent }}" {{ old('continent') == $continent ? 'selected' : '' }}>
                            {{ $continent }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('continent')" class="mt-2" />
            </div>

            <!-- Country -->
            <div class="mt-4">
                <x-input-label for="country" :value="__('Country')" />
                <x-text-input id="country" class="block mt-1 w-full" type="text" name="country"
                    :value="old('country')" />
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>

            <!-- City -->
            <div class="mt-4">
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city"
                    :value="old('city')" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>
        </div>

        <!-- Step 3: Social Media (Optional) -->
        <div id="step3" class="step">
            <h2 class="text-lg font-medium text-black mb-4">Step 3: Social Media (Optional)</h2>
            <!-- Website, Facebook, LinkedIn, Twitter, Instagram inputs remain the same -->
            <!-- Website -->
            <div>
                <x-input-label for="website" :value="__('Website')" />
                <x-text-input id="website" class="block mt-1 w-full" type="url" name="website"
                    :value="old('website')" />
                <x-input-error :messages="$errors->get('website')" class="mt-2" />
            </div>

            <!-- Facebook -->
            <div class="mt-4">
                <x-input-label for="facebook" :value="__('Facebook')" />
                <x-text-input id="facebook" class="block mt-1 w-full" type="url" name="facebook"
                    :value="old('facebook')" />
                <x-input-error :messages="$errors->get('facebook')" class="mt-2" />
            </div>

            <!-- LinkedIn -->
            <div class="mt-4">
                <x-input-label for="linkedin" :value="__('LinkedIn')" />
                <x-text-input id="linkedin" class="block mt-1 w-full" type="url" name="linkedin"
                    :value="old('linkedin')" />
                <x-input-error :messages="$errors->get('linkedin')" class="mt-2" />
            </div>

            <!-- Twitter -->
            <div class="mt-4">
                <x-input-label for="twitter" :value="__('Twitter')" />
                <x-text-input id="twitter" class="block mt-1 w-full" type="url" name="twitter"
                    :value="old('twitter')" />
                <x-input-error :messages="$errors->get('twitter')" class="mt-2" />
            </div>

            <!-- Instagram -->
            <div class="mt-4">
                <x-input-label for="instagram" :value="__('Instagram')" />
                <x-text-input id="instagram" class="block mt-1 w-full" type="url" name="instagram"
                    :value="old('instagram')" />
                <x-input-error :messages="$errors->get('instagram')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <div>
                <x-secondary-button type="button" id="prevBtn" onclick="nextPrev(-1)" class="hidden">
                    {{ __('Previous') }}
                </x-secondary-button>

                <x-primary-button type="button" id="nextBtn" onclick="nextPrev(1)">
                    {{ __('Next') }}
                </x-primary-button>

                <x-primary-button type="submit" id="submitBtn" class="hidden">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    <script>
        let currentStep = 1;
        const form = document.getElementById('multiStepForm');
        showStep(currentStep);

        // This function handles the case where a backend validation error occurs.
        // It checks which step contains an error and displays it.
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.getElementsByClassName('step');
            for (let i = 0; i < steps.length; i++) {
                if (steps[i].querySelector('.text-sm.text-red-600')) {
                    currentStep = i + 1;
                    showStep(currentStep);
                    break;
                }
            }
        });


        function showStep(step) {
            const steps = document.getElementsByClassName('step');
            for (let i = 0; i < steps.length; i++) {
                steps[i].classList.remove('active');
            }
            steps[step - 1].classList.add('active');

            document.getElementById('prevBtn').classList.toggle('hidden', step === 1);
            document.getElementById('nextBtn').classList.toggle('hidden', step === steps.length);
            document.getElementById('submitBtn').classList.toggle('hidden', step !== steps.length);
        }

        function nextPrev(n) {
            const steps = document.getElementsByClassName('step');
            // Only validate when moving forward
            if (n === 1 && !validateStep(currentStep)) {
                return false;
            }

            steps[currentStep - 1].classList.remove('active');
            currentStep += n;

            if (currentStep > steps.length) {
                form.submit();
                return false;
            }
            showStep(currentStep);
        }

        function validateStep(step) {
            let valid = true;
            const currentStepElement = document.getElementById(`step${step}`);
            const inputs = currentStepElement.querySelectorAll('input[required], select[required]');

            // Clear previous frontend validation styles and messages
            currentStepElement.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
            currentStepElement.querySelectorAll('.validation-error-message').forEach(el => el.remove());

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('border-red-500');
                }
            });

            // Custom validation for Step 2 services checkboxes
            if (step === 2) {
                const servicesContainer = document.getElementById('services-container');
                const isServiceChecked = servicesContainer.querySelector('input[name="services[]"]:checked');

                if (!isServiceChecked) {
                    valid = false;
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'text-sm text-red-600 mt-2 validation-error-message';
                    errorDiv.textContent = 'Please select at least one service.';
                    // Append error after the checkbox group
                    servicesContainer.querySelector('.space-y-2').insertAdjacentElement('afterend', errorDiv);
                }
            }

            return valid;
        }
    </script>
</x-guest-layout>
