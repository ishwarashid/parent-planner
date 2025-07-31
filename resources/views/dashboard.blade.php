<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Welcome, {{ Auth::user()->name }}!</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Next Visit Card -->
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-blue-800">Next Visitation</h4>
                        @if ($nextVisit)
                            <p class="text-2xl font-bold text-blue-900">{{ \Carbon\Carbon::parse($nextVisit->date_start)->diffForHumans() }}</p>
                            <p class="text-sm text-blue-700">{{ $nextVisit->child->name }} on {{ \Carbon\Carbon::parse($nextVisit->date_start)->format('M d, Y H:i A') }}</p>
                            <a href="{{ route('visitations.show', $nextVisit) }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 block">View Details</a>
                        @else
                            <p class="text-gray-600">No upcoming visitations.</p>
                            <a href="{{ route('visitations.create') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 block">Add a Visitation</a>
                        @endif
                    </div>

                    @can('create', App\Models\Expense::class)
                    <!-- Pending Expenses Card -->
                    <div class="bg-yellow-100 p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-yellow-800">Pending Expenses</h4>
                        @if ($pendingExpenses->count() > 0)
                            <p class="text-2xl font-bold text-yellow-900">{{ $pendingExpenses->count() }}</p>
                            <p class="text-sm text-yellow-700">Total: ${{ number_format($pendingExpenses->sum('amount'), 2) }}</p>
                            <a href="{{ route('expenses.index') }}" class="text-yellow-600 hover:text-yellow-800 text-sm mt-2 block">View All Pending</a>
                        @else
                            <p class="text-gray-600">No pending expenses.</p>
                            <a href="{{ route('expenses.create') }}" class="text-yellow-600 hover:text-yellow-800 text-sm mt-2 block">Add an Expense</a>
                        @endif
                    </div>
                    @endcan

                    @can('create', App\Models\Child::class)
                    <!-- Upcoming Birthdays Card -->
                    <div class="bg-purple-100 p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-purple-800">Upcoming Birthdays</h4>
                        @if ($childrenWithUpcomingBirthdays->count() > 0)
                            <ul class="list-disc list-inside text-sm text-purple-700">
                                @foreach ($childrenWithUpcomingBirthdays as $child)
                                    <li>{{ $child->name }} ({{ \Carbon\Carbon::parse($child->dob)->format('M d') }}) - {{ \Carbon\Carbon::parse($child->dob)->diffForHumans(null, true, false, 1) }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ route('children.index') }}" class="text-purple-600 hover:text-purple-800 text-sm mt-2 block">View Children</a>
                        @else
                            <p class="text-gray-600">No upcoming birthdays.</p>
                        @endif
                    </div>
                    @endcan
                </div>

                <!-- Recent Activity (Optional - can be added later) -->
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                <div class="bg-gray-50 p-4 rounded-lg shadow-inner" style="min-height: 150px;">
                    <p class="text-center text-gray-500">Recent activities (e.g., new visitations, expenses, documents) can be displayed here.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>