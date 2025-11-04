<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Professional Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Professional Profile</h1>

                <form method="POST" action="{{ route('professional.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">Business Name *</label>
                            <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $user->professional->business_name) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('business_name') border-red-500 @enderror"
                                   required>
                            @error('business_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->professional->phone_number) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('phone_number') border-red-500 @enderror"
                                   required>
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Services *</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto p-2 border border-gray-200 rounded-md">
                            @foreach($services as $service)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="service_{{ Str::slug($service) }}" name="services[]" type="checkbox" value="{{ $service }}"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded @error('services') border-red-500 @enderror service-checkbox"
                                               {{ in_array($service, old('services', $user->professional->services ?: [])) ? 'checked' : '' }}
                                               @if($service === 'Other') data-other-checkbox="true" @endif>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="service_{{ Str::slug($service) }}" class="font-medium text-gray-700">
                                            {{ $service }}
                                            @if($service === 'Other')
                                                <span id="other-service-label" class="text-gray-500 text-xs italic"> (specify below)</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('services')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Custom service input that appears when 'Other' is checked -->
                        <div id="custom-service-container" class="mt-4" 
                             style="{{ (in_array('Other', old('services', $user->professional->services ?: [])) || $customService) ? 'display: block;' : 'display: none;' }}">
                            <label for="custom_service" class="block text-sm font-medium text-gray-700 mb-1">
                                Please specify your service:
                            </label>
                            <input type="text" name="custom_service" id="custom_service" value="{{ old('custom_service', $customService) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Enter your specific service type</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="continent" class="block text-sm font-medium text-gray-700 mb-1">Continent *</label>
                            <select name="continent" id="continent" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('continent') border-red-500 @enderror"
                                    required>
                                <option value="">Select Continent</option>
                                @foreach($continents as $continent)
                                    <option value="{{ $continent }}" {{ old('continent', $user->professional->continent) == $continent ? 'selected' : '' }}>
                                        {{ $continent }}
                                    </option>
                                @endforeach
                            </select>
                            @error('continent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                            <input type="text" name="country" id="country" value="{{ old('country', $user->professional->country) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('country') border-red-500 @enderror"
                                   required>
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $user->professional->city) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('city') border-red-500 @enderror"
                                   required>
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website', $user->professional->website) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('website') border-red-500 @enderror">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                            <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $user->professional->facebook) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('facebook') border-red-500 @enderror">
                            @error('facebook')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                            <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $user->professional->linkedin) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('linkedin') border-red-500 @enderror">
                            @error('linkedin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="twitter" class="block text-sm font-medium text-gray-700 mb-1">Twitter</label>
                            <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $user->professional->twitter) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('twitter') border-red-500 @enderror">
                            @error('twitter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                        <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $user->professional->instagram) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('instagram') border-red-500 @enderror">
                        @error('instagram')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Professional Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otherCheckboxes = document.querySelectorAll('input[data-other-checkbox="true"]');
            const customServiceContainer = document.getElementById('custom-service-container');
            
            // Add change event listeners to all 'Other' checkboxes
            otherCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    // Check if any 'Other' checkbox is checked
                    const anyOtherChecked = Array.from(otherCheckboxes).some(cb => cb.checked);
                    
                    if (anyOtherChecked) {
                        customServiceContainer.style.display = 'block';
                    } else {
                        customServiceContainer.style.display = 'none';
                    }
                });
            });
            
            // Initialize state on page load
            const initialOtherChecked = Array.from(otherCheckboxes).some(cb => cb.checked);
            const hasCustomService = "{{ $customService }}".length > 0;
            if (initialOtherChecked || hasCustomService) {
                customServiceContainer.style.display = 'block';
            }
        });
    </script>
</x-app-layout>