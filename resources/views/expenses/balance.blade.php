<style>
    /* Theme color variables */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-body-text: #1D2951;
        /* Muted Navy */
        --theme-button-bg: #40E0D0;
        /* Turquoise */
        --theme-button-text: #000033;
        --theme-button-bg-hover: #48D1CC;
        /* Medium Turquoise */

        --theme-alert-bg: #AFEEEE;
        /* Pale Turquoise */
        --theme-alert-border: #00CED1;
        /* Dark Turquoise */
        --theme-alert-text: #2F4F4F;
        /* Dark Slate Gray */

        /* Action Link Colors */
        --theme-action-view: #008080;
        /* Teal */
        --theme-action-edit: #3F51B5;
        /* Indigo */
        --theme-action-delete: #FF6F61;
        /* Coral */
    }

    /* General Styling */
    .theme-header-text {
        color: var(--theme-header);
    }

    .theme-button {
        background-color: var(--theme-button-bg);
        color: var(--theme-button-text);
        font-weight: 700;
        transition: background-color 0.2s;
    }

    .theme-button:hover {
        background-color: var(--theme-button-bg-hover);
    }

    /* Success Alert */
    .theme-alert-success {
        background-color: var(--theme-alert-bg);
        border-color: var(--theme-alert-border);
        color: var(--theme-alert-text);
    }

    /* Table Styling */
    .theme-table-header {
        background-color: #f9fafb;
    }

    .theme-table-header-text {
        color: var(--theme-body-text);
    }

    .theme-table-row-text {
        color: var(--theme-body-text);
    }

    tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Table Action Links */
    .theme-action-link {
        font-weight: 500;
        transition: all 0.2s;
    }

    .theme-action-link:hover {
        text-decoration: underline;
        opacity: 0.8;
    }

    .theme-action-view {
        color: var(--theme-action-view);
    }

    .theme-action-edit {
        color: var(--theme-action-edit);
    }

    .theme-action-delete {
        color: var(--theme-action-delete);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Expense Balances') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('expenses.index') }}" class="theme-button py-2 px-4 rounded">
                            ← Back to Expenses
                        </a>
                    </div>

                    <h3 class="text-lg font-semibold theme-header-text mb-4">Balance Summary</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="theme-table-header">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Parent
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Total Paid
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Total Owed
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Opening Balance
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Net Balance
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($balances as $balance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium theme-table-row-text">
                                                {{ $balance['user']->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                ${{ number_format($balance['total_paid'], 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                ${{ number_format($balance['total_owed'], 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                ${{ number_format($balance['opening_balance'], 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold theme-table-row-text">
                                                ${{ number_format($balance['net_balance'], 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($balance['net_balance'] > 0)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Owed to you
                                                </span>
                                            @elseif($balance['net_balance'] < 0)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    You owe
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Settled
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            No family members found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Opening Balance Form -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold theme-header-text mb-4">Set Opening Balance</h3>
                        <form method="POST" action="{{ route('expenses.opening-balance.store') }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Family Member</label>
                                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Select a member</option>
                                        @php
                                            $user = auth()->user();
                                            $familyMemberIds = $user->getFamilyMemberIds();
                                            if (!in_array($user->id, $familyMemberIds)) {
                                                $familyMemberIds[] = $user->id;
                                            }
                                            $familyMembers = \App\Models\User::whereIn('id', $familyMemberIds)->get();
                                        @endphp
                                        @foreach($familyMembers as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                    <input type="number" name="amount" id="amount" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="0.00" required>
                                </div>
                                
                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" name="date" id="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                </div>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                    <input type="text" name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Opening balance description">
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="theme-button py-2 px-4 rounded">
                                    Save Opening Balance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>