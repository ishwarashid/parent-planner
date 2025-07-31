<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Generate Visitation Report</h3>
                    <form method="GET" action="{{ route('reports.visitations') }}" class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="child_id_visitation" :value="__('Child')" />
                                <select id="child_id_visitation" name="child_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All Children</option>
                                    @foreach ($children as $child)
                                        <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="start_date_visitation" :value="__('Start Date')" />
                                <x-text-input id="start_date_visitation" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date')" />
                            </div>
                            <div>
                                <x-input-label for="end_date_visitation" :value="__('End Date')" />
                                <x-text-input id="end_date_visitation" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date')" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="format_visitation" :value="__('Format')" />
                            <select id="format_visitation" name="format" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Generate Visitation Report') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <hr class="my-8">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Generate Expense Report</h3>
                    <form method="GET" action="{{ route('reports.expenses') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="child_id_expense" :value="__('Child')" />
                                <select id="child_id_expense" name="child_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All Children</option>
                                    @foreach ($children as $child)
                                        <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="start_date_expense" :value="__('Start Date')" />
                                <x-text-input id="start_date_expense" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date')" />
                            </div>
                            <div>
                                <x-input-label for="end_date_expense" :value="__('End Date')" />
                                <x-text-input id="end_date_expense" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date')" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="category_expense" :value="__('Category')" />
                                <select id="category_expense" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All Categories</option>
                                    @foreach (['Healthcare', 'Education', 'Childcare', 'Food', 'Clothing', 'Activities', 'Other'] as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="status_expense" :value="__('Status')" />
                                <select id="status_expense" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All Statuses</option>
                                    @foreach (['pending', 'paid', 'disputed'] as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="format_expense" :value="__('Format')" />
                            <select id="format_expense" name="format" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Generate Expense Report') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>