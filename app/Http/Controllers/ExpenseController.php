<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseOpeningBalance;
use App\Models\User;
use App\Services\ExpenseBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Expense::class, 'expense');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // <-- Inject the Request object
    {
        $this->authorize('viewAny', Expense::class);

        // Get the status filter from the URL query string (e.g., /expenses?status=pending)
        $statusFilter = $request->query('status');
        $statuses = ['pending', 'paid', 'disputed']; // Define valid statuses

        $user = auth()->user()->load('invitedUsers');
        $familyMemberIds = $user->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();

        // Start building the query
        $query = Expense::with(['child', 'payer', 'splits.user'])
            ->whereIn('child_id', $children->pluck('id'));

        // If a valid status filter is present, add it to the query
        if ($statusFilter && in_array($statusFilter, $statuses)) {
            $query->where('status', $statusFilter);
        }

        // Execute the final query
        $expenses = $query->latest()->get();

        // Pass the statuses and the current filter back to the view
        return view('expenses.index', compact('expenses', 'statuses', 'statusFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Expense::class);
        $user = auth()->user();
        $familyMemberIds = $user->getFamilyMemberIds();
        if (!in_array($user->id, $familyMemberIds)) {
            $familyMemberIds[] = $user->id;
        }

        // This list is still needed for the "Responsibility Split" section.
        $responsibleUsers = User::whereIn('id', $familyMemberIds)
            ->whereIn('role', ['parent', 'co-parent'])
            ->get();

        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        // $children = \App\Models\Child::whereIn('user_id', 'familyMemberIds')->get();
        $categories = ['Healthcare', 'Education', 'Childcare', 'Food', 'Clothing', 'Activities', 'Other'];
        $statuses = ['pending', 'paid', 'disputed'];

        return view('expenses.create', compact('children', 'categories', 'statuses', 'responsibleUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Expense::class);

        $validatedData = $request->validate([
            'child_id' => 'required|exists:children,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'status' => 'required|string|in:pending,paid,disputed',
            'receipt_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'is_recurring' => 'nullable|boolean',
            'recurrence_pattern' => 'nullable|required_if:is_recurring,1|in:daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'nullable|required_if:is_recurring,1|date|after_or_equal:today',
            // REMOVED: Validation for payer_id is no longer needed from the form.
            'splits' => 'required|array',
            'splits.*.user_id' => 'required|exists:users,id',
            'splits.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Server-side validation that percentages sum to 100
        $totalPercentage = collect($validatedData['splits'])->sum('percentage');
        if (abs($totalPercentage - 100.00) > 0.01) {
            return back()->withErrors(['splits' => 'The percentages must add up to 100%.'])->withInput();
        }

        // SET PAYER: The authenticated user is always the payer.
        $validatedData['payer_id'] = auth()->id();

        // Handle recurring fields
        if ($request->has('is_recurring')) {
            $validatedData['is_recurring'] = true;
        } else {
            $validatedData['is_recurring'] = false;
        }

        DB::transaction(function () use ($validatedData, $request) {
            if ($request->hasFile('receipt_url')) {
                $path = $request->file('receipt_url')->store('receipts', 'do');
                $validatedData['receipt_url'] = $path;
            }

            $expense = Expense::create($validatedData);

            foreach ($validatedData['splits'] as $split) {
                $expense->splits()->create([
                    'user_id' => $split['user_id'],
                    'percentage' => $split['percentage'],
                ]);
            }
        });

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        $expense->load(['child', 'payer', 'splits.user', 'confirmations']); // Eager load confirmations for the new feature
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        // You can only edit expenses you created/paid for
        if ($expense->payer_id !== auth()->id()) {
            abort(403, 'You can only edit expenses that you paid for.');
        }

        $user = auth()->user();
        $familyMemberIds = $user->getFamilyMemberIds();
        if (!in_array($user->id, $familyMemberIds)) {
            $familyMemberIds[] = $user->id;
        }

        $responsibleUsers = User::whereIn('id', $familyMemberIds)
            ->whereIn('role', ['parent', 'co-parent'])
            ->get();

        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $categories = ['Healthcare', 'Education', 'Childcare', 'Food', 'Clothing', 'Activities', 'Other'];
        $statuses = ['pending', 'paid', 'disputed'];
        $expense->load('splits.user');

        return view('expenses.edit', compact('expense', 'children', 'categories', 'statuses', 'responsibleUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        \Log::info('Expense Update Request Data:', $request->all());
        \Log::info('Expense Update Has File receipt_url:', [$request->hasFile('receipt_url')]);
        if ($request->hasFile('receipt_url')) {
            \Log::info('Expense Update Receipt File Info:', [
                'file_name' => $request->file('receipt_url')->getClientOriginalName(),
                'file_size' => $request->file('receipt_url')->getSize(),
                'file_mime' => $request->file('receipt_url')->getMimeType()
            ]);
        }

        \Log::info('About to validate request data');
        $validatedData = $request->validate([
            'child_id' => 'required|exists:children,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'status' => 'required|string|in:pending,paid,disputed',
            'receipt_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'is_recurring' => 'nullable|boolean',
            'recurrence_pattern' => 'nullable|required_if:is_recurring,1|in:daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'nullable|required_if:is_recurring,1|date|after_or_equal:today',
            // REMOVED: Payer ID is not updatable.
            'splits' => 'required|array',
            'splits.*.user_id' => 'required|exists:users,id',
            'splits.*.percentage' => 'nullable|numeric|min:0|max:100',
        ]);
        \Log::info('Validation passed, splits data:', ['splits' => $validatedData['splits'] ?? 'not set']);

        // Filter out splits with null or zero percentages
        $splits = collect($validatedData['splits'])->filter(function ($split) {
            return !is_null($split['percentage']) && $split['percentage'] > 0;
        })->toArray();

        $validatedData['splits'] = $splits;

        $totalPercentage = collect($splits)->sum('percentage');
        \Log::info('Calculated total percentage:', ['total' => $totalPercentage]);
        if (abs($totalPercentage - 100.00) > 0.01) {
            \Log::info('Percentage validation failed');
            return back()->withErrors(['splits' => 'The percentages must add up to 100%.'])->withInput();
        }

        // Handle recurring fields
        if ($request->has('is_recurring')) {
            $validatedData['is_recurring'] = true;
        } else {
            $validatedData['is_recurring'] = false;
        }

        DB::transaction(function () use ($validatedData, $request, $expense) {
            if ($request->hasFile('receipt_url')) {
                \Log::info('About to delete existing receipt if exists');
                if ($expense->receipt_url) {
                    \Log::info('Deleting existing receipt: ' . $expense->receipt_url);
                    Storage::disk('do')->delete($expense->receipt_url);
                }
                \Log::info('About to store new receipt');
                $validatedData['receipt_url'] = $request->file('receipt_url')->store('receipts', 'do');
                \Log::info('Receipt stored with path: ' . ($validatedData['receipt_url'] ?? 'NULL'));
            }

            $expense->update($validatedData);
            $expense->splits()->delete();

            foreach ($validatedData['splits'] as $split) {
                $expense->splits()->create([
                    'user_id' => $split['user_id'],
                    'percentage' => $split['percentage'],
                ]);
            }
        });

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        if ($expense->receipt_url) {
            Storage::disk('do')->delete($expense->receipt_url);
        }

        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    /**
     * Display a balance summary for all family members.
     */
    public function balanceSummary()
    {
        $this->authorize('viewAny', Expense::class);

        $user = auth()->user();
        $familyMemberIds = $user->getFamilyMemberIds();
        
        if (!in_array($user->id, $familyMemberIds)) {
            $familyMemberIds[] = $user->id;
        }

        $balanceService = new ExpenseBalanceService();
        $balances = $balanceService->calculateBalances($familyMemberIds);

        return view('expenses.balance', compact('balances'));
    }

    /**
     * Store an opening balance for a user.
     */
    public function storeOpeningBalance(Request $request)
    {
        $user = auth()->user();
        $familyMemberIds = $user->getFamilyMemberIds();
        
        $request->validate([
            'user_id' => 'required|in:' . implode(',', $familyMemberIds),
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        ExpenseOpeningBalance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'date' => $request->date,
            ],
            [
                'amount' => $request->amount,
                'description' => $request->description,
            ]
        );

        return redirect()->back()->with('success', 'Opening balance saved successfully.');
    }
}
