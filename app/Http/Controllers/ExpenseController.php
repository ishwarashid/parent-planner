<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Expense::class);
        $expenses = Expense::whereIn('child_id', auth()->user()->children->pluck('id'))->get();
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Expense::class);
        $children = auth()->user()->children;
        $categories = ['Healthcare', 'Education', 'Childcare', 'Food', 'Clothing', 'Activities', 'Other'];
        $statuses = ['pending', 'paid', 'disputed'];
        return view('expenses.create', compact('children', 'categories', 'statuses'));
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
        ]);

        $validatedData['payer_id'] = auth()->id();

        if ($request->hasFile('receipt_url')) {
            $path = $request->file('receipt_url')->store('receipts', 'public');
            $validatedData['receipt_url'] = $path;
        }

        Expense::create($validatedData);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        $children = auth()->user()->children;
        $categories = ['Healthcare', 'Education', 'Childcare', 'Food', 'Clothing', 'Activities', 'Other'];
        $statuses = ['pending', 'paid', 'disputed'];
        return view('expenses.edit', compact('expense', 'children', 'categories', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $validatedData = $request->validate([
            'child_id' => 'required|exists:children,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'status' => 'required|string|in:pending,paid,disputed',
            'receipt_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        if ($request->hasFile('receipt_url')) {
            // Delete old receipt if it exists
            if ($expense->receipt_url) {
                Storage::disk('public')->delete($expense->receipt_url);
            }
            $path = $request->file('receipt_url')->store('receipts', 'public');
            $validatedData['receipt_url'] = $path;
        }

        $expense->update($validatedData);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        // Delete associated receipt file if it exists
        if ($expense->receipt_url) {
            Storage::disk('public')->delete($expense->receipt_url);
        }

        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
