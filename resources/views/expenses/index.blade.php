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
            {{ __('Expenses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- NEW: Filter and Actions Section --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">

                        {{-- Filter Form --}}
                        <form action="{{ route('expenses.index') }}" method="GET" class="flex items-center gap-2">
                            <label for="status" class="text-sm font-medium theme-body-text">Filter by status:</label>
                            <select name="status" id="status"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ $statusFilter == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="py-2 px-4 rounded theme-button text-sm">Filter</button>
                            @if ($statusFilter)
                                <a href="{{ route('expenses.index') }}"
                                    class="text-sm text-gray-600 hover:text-gray-900">Clear</a>
                            @endif
                        </form>

                        {{-- Add Expense Button and Balance Summary Link --}}
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto justify-center sm:justify-end">
                            <a href="{{ route('expenses.balances') }}"
                                class="py-2 px-4 rounded theme-button w-full sm:w-auto text-center">
                                View Balances
                            </a>
                            <a href="{{ route('expenses.create') }}"
                                class="py-2 px-4 rounded theme-button w-full sm:w-auto text-center">
                                Add New Expense
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Balance Summary Section --}}
                    @php
                        $user = auth()->user();
                        $familyMemberIds = $user->getFamilyMemberIds();
                        if (!in_array($user->id, $familyMemberIds)) {
                            $familyMemberIds[] = $user->id;
                        }
                        $balanceService = new \App\Services\ExpenseBalanceService();
                        $balances = $balanceService->calculateBalances($familyMemberIds);
                        $currentUserBalance = collect($balances)->firstWhere('user.id', $user->id);
                    @endphp

                    @if($currentUserBalance)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h3 class="text-md font-semibold theme-header-text mb-2">Your Balance Summary</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Total Paid</p>
                                <p class="font-bold text-lg">${{ number_format($currentUserBalance['total_paid'], 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Total Owed</p>
                                <p class="font-bold text-lg">${{ number_format($currentUserBalance['total_owed'], 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Opening Balance</p>
                                <p class="font-bold text-lg">${{ number_format($currentUserBalance['opening_balance'], 2) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Net Balance</p>
                                <p class="font-bold text-lg @if($currentUserBalance['net_balance'] > 0) text-green-600 @elseif($currentUserBalance['net_balance'] < 0) text-red-600 @else text-gray-600 @endif">
                                    ${{ number_format($currentUserBalance['net_balance'], 2) }}
                                </p>
                            </div>
                        </div>
                        @if($currentUserBalance['net_balance'] != 0)
                            <div class="mt-3 text-sm">
                                @if($currentUserBalance['net_balance'] > 0)
                                    <span class="text-green-600">Others owe you money.</span>
                                @else
                                    <span class="text-red-600">You owe money to others.</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    @endif

                    @if (session('success'))
                        {{-- ... your success message div ... --}}
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- ... your existing table structure remains exactly the same ... --}}
                            <thead class="theme-table-header">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Child
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Description
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Total Amount
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Your Share
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Created by
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Date Added
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($expenses as $expense)
                                    <tr>
                                        {{-- All your existing <td> cells go here --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium theme-table-row-text">
                                                {{ $expense->child->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                {{ Str::limit($expense->description, 30) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                ${{ number_format($expense->amount, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $userSplit = $expense->splits->firstWhere('user_id', auth()->id());
                                                $userShare = $userSplit
                                                    ? $expense->amount * ($userSplit->percentage / 100)
                                                    : 0;
                                            @endphp
                                            <div class="text-sm font-bold theme-table-row-text">
                                                ${{ number_format($userShare, 2) }}
                                                @if ($userSplit)
                                                    <span
                                                        class="text-xs text-gray-500">({{ $userSplit->percentage }}%)</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                {{ $expense->payer->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                {{ $expense->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $expense->status == 'paid' ? 'bg-green-100 text-green-800' : ($expense->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($expense->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('view', $expense)
                                                <a href="{{ route('expenses.show', $expense) }}"
                                                    class="mr-3 theme-action-link theme-action-view">View</a>
                                            @endcan
                                            @can('update', $expense)
                                                <a href="{{ route('expenses.edit', $expense) }}"
                                                    class="mr-3 theme-action-link theme-action-edit">Edit</a>
                                            @endcan
                                            @can('delete', $expense)
                                                <form class="inline-block"
                                                    action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this expense?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="theme-action-link theme-action-delete">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{-- Show a helpful message when there are no results for the filter --}}
                                            @if ($statusFilter)
                                                No expenses found with the status "{{ ucfirst($statusFilter) }}".
                                            @else
                                                No expenses found.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
