<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class PaymentConfirmationController extends Controller
{
    public function store(Request $request, Expense $expense)
    {
        // You cannot confirm your own expense if you are the payer
        if ($expense->payer_id === auth()->id()) {
            return back()->with('error', 'You cannot confirm an expense you paid for.');
        }

        // Check if already confirmed to prevent duplicates
        $alreadyConfirmed = $expense->confirmations()->where('user_id', auth()->id())->exists();
        if ($alreadyConfirmed) {
            return back()->with('info', 'You have already confirmed payment for this expense.');
        }

        // Create the confirmation record
        $expense->confirmations()->create([
            'user_id' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        // You might also want to send a notification to the payer here

        return back()->with('success', 'You have successfully confirmed your payment.');
    }
}
