<style>
    /* Theme color variables */
    :root {
        --theme-text-dark-navy: #000033;
        --theme-text-navy: #1D2951;
        --theme-accent-turquoise: #40E0D0;
        --theme-accent-light-turquoise-bg: rgba(64, 224, 208, 0.15);
        /* For card backgrounds */
    }

    /* Header Styling */
    .theme-header-text {
        color: var(--theme-text-dark-navy);
    }

    /* Main Content Styling */
    .theme-section-title {
        color: var(--theme-text-dark-navy);
    }

    /* Card Styling */
    .theme-card {
        background-color: var(--theme-accent-light-turquoise-bg);
        color: var(--theme-text-navy);
    }

    .theme-card-header {
        color: var(--theme-text-dark-navy);
        font-weight: 600;
    }

    .theme-card-highlight {
        color: var(--theme-text-dark-navy);
        font-weight: 800;
        /* bolder */
    }

    .theme-card-body {
        color: var(--theme-text-navy);
    }

    .theme-card-link {
        color: #215C5C;
        /* A darker turquoise for better readability */
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
    }

    .theme-card-link:hover {
        color: var(--theme-text-dark-navy);
        text-decoration: underline;
    }

    .theme-card-placeholder {
        background-color: rgba(229, 231, 235, 0.5);
        /* A subtle gray for placeholders */
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4 theme-section-title">Welcome, {{ Auth::user()->name }}!</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Next Visit Card -->
                    <div class="p-4 rounded-lg shadow theme-card">
                        <h4 class="theme-card-header">Next Visitation</h4>
                        @if ($nextVisit)
                            <p class="text-2xl theme-card-highlight">
                                {{ \Carbon\Carbon::parse($nextVisit->date_start)->diffForHumans() }}</p>
                            <p class="text-sm theme-card-body">{{ $nextVisit->child->name }} on
                                {{ \Carbon\Carbon::parse($nextVisit->date_start)->format('M d, Y H:i A') }}</p>
                            <a href="{{ route('visitations.show', $nextVisit) }}"
                                class="mt-2 block text-sm theme-card-link">View Details</a>
                        @else
                            <p class="text-gray-600">No upcoming visitations.</p>
                            <a href="{{ route('visitations.index') }}" class="mt-2 block text-sm theme-card-link">Add a
                                Visitation</a>
                        @endif
                    </div>

                    <!-- Pending Expenses Card -->
                    <div class="p-4 rounded-lg shadow theme-card">
                        <h4 class="theme-card-header">Pending Expenses</h4>
                        @if ($pendingExpenses->count() > 0)
                            <p class="text-2xl theme-card-highlight">{{ $pendingExpenses->count() }}</p>
                            <p class="text-sm theme-card-body">Total:
                                ${{ number_format($pendingExpenses->sum('amount'), 2) }}</p>
                            <a href="{{ route('expenses.index') }}" class="mt-2 block text-sm theme-card-link">View All
                                Pending</a>
                        @else
                            <p class="text-gray-600">No pending expenses.</p>
                            <a href="{{ route('expenses.create') }}" class="mt-2 block text-sm theme-card-link">Add an
                                Expense</a>
                        @endif
                    </div>
                    
                    <!-- Upcoming Birthdays Card -->
                    <div class="p-4 rounded-lg shadow theme-card">
                        <h4 class="theme-card-header">Upcoming Birthdays</h4>
                        @if ($childrenWithUpcomingBirthdays->count() > 0)
                            <ul class="list-disc list-inside text-sm theme-card-body">
                                @foreach ($childrenWithUpcomingBirthdays as $child)
                                    <li>{{ $child->name }} ({{ \Carbon\Carbon::parse($child->dob)->format('M d') }})
                                        - in
                                        {{ \Carbon\Carbon::parse($child->dob)->diffForHumans(null, true, false, 1) }}
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('children.index') }}" class="mt-2 block text-sm theme-card-link">View
                                Children</a>
                        @else
                            <p class="text-gray-600">No upcoming birthdays.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <h3 class="text-lg font-medium mb-4 theme-section-title">Recent Activity</h3>
                <div class="p-4 rounded-lg shadow-inner theme-card-placeholder" style="min-height: 150px;">
                    <p class="text-center text-gray-500 pt-12">Recent activities will be displayed here.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
