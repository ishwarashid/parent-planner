<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visitation Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold">Visitation for {{ $visitation->child->name }}</h3>
                        {{-- Changed "With" to "Assigned To" for clarity --}}
                        <p class="text-gray-600">Assigned To: {{ $visitation->parent->name }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <dl class="divide-y divide-gray-200">
                            <!-- NEW: Status Field -->
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Status</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if ($visitation->status == 'Scheduled') bg-blue-100 text-blue-800
                                        @elseif($visitation->status == 'Completed') bg-green-100 text-green-800
                                        @elseif($visitation->status == 'Cancelled') bg-gray-100 text-gray-800 @endif">
                                        {{ $visitation->status }}
                                    </span>
                                </dd>
                            </div>

                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Start Time</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ \Carbon\Carbon::parse($visitation->date_start)->format('M d, Y, h:i A') }}</dd>
                            </div>

                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">End Time</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ \Carbon\Carbon::parse($visitation->date_end)->format('M d, Y, h:i A') }}</dd>
                            </div>

                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Duration</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ \Carbon\Carbon::parse($visitation->date_start)->diffForHumans($visitation->date_end, true) }}
                                </dd>
                            </div>

                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Recurring</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ $visitation->is_recurring ? 'Yes' : 'No' }}</dd>
                            </div>

                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Notes</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ $visitation->notes ?? 'N/A' }}</dd>
                            </div>

                            <!-- NEW: Created By Field -->
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Created By</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ $visitation->creator->name ?? 'N/A' }}</dd>
                            </div>

                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Record Created</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ \Carbon\Carbon::parse($visitation->created_at)->format('M d, Y, h:i A') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6 flex justify-end items-center space-x-2">
                        {{-- Added @can directives to show/hide buttons based on policy rules --}}
                        @can('update', $visitation)
                            <a href="{{ route('visitations.edit', $visitation) }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit
                            </a>
                        @endcan

                        @can('delete', $visitation)
                            <form action="{{ route('visitations.destroy', $visitation) }}" method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to delete this visitation?')">
                                    Delete
                                </button>
                            </form>
                        @endcan

                        <a href="{{ route('visitations.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
