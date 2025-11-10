<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\ExpenseOpeningBalance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExpenseBalanceService
{
    public function calculateBalances($familyMemberIds)
    {
        $balances = [];
        
        foreach ($familyMemberIds as $userId) {
            $user = User::find($userId);
            
            // Calculate total paid by user (as payer) for paid expenses
            $totalPaid = Expense::where('payer_id', $userId)
                ->where('status', 'paid')
                ->sum('amount');
                
            // Calculate total owed by user from splits in paid expenses
            $totalOwed = ExpenseSplit::where('user_id', $userId)
                ->join('expenses', 'expense_splits.expense_id', '=', 'expenses.id')
                ->where('expenses.status', 'paid') // Only count paid expenses towards what's owed
                ->sum(DB::raw('expenses.amount * expense_splits.percentage / 100'));
            
            // Get opening balance if exists
            $openingBalance = ExpenseOpeningBalance::where('user_id', $userId)
                ->sum('amount');
            
            // Calculate net balance: (what they paid) - (what they owe) + opening_balance
            // If positive, they are owed money; if negative, they owe money
            $netBalance = $totalPaid - $totalOwed + $openingBalance;
            
            $balances[] = [
                'user' => $user,
                'total_paid' => $totalPaid,
                'total_owed' => $totalOwed,
                'opening_balance' => $openingBalance,
                'net_balance' => $netBalance
            ];
        }
        
        return $balances;
    }

    public function getUserBalance($userId)
    {
        // Calculate total paid by user (as payer) for paid expenses
        $totalPaid = Expense::where('payer_id', $userId)
            ->where('status', 'paid')
            ->sum('amount');
            
        // Calculate total owed by user from splits in paid expenses
        $totalOwed = ExpenseSplit::where('user_id', $userId)
            ->join('expenses', 'expense_splits.expense_id', '=', 'expenses.id')
            ->where('expenses.status', 'paid') // Only count paid expenses
            ->sum(DB::raw('expenses.amount * expense_splits.percentage / 100'));
        
        // Get opening balance if exists
        $openingBalance = ExpenseOpeningBalance::where('user_id', $userId)
            ->sum('amount');
        
        // Calculate net balance: (what they paid) - (what they owe) + opening_balance
        $netBalance = $totalPaid - $totalOwed + $openingBalance;
        
        return [
            'total_paid' => $totalPaid,
            'total_owed' => $totalOwed,
            'opening_balance' => $openingBalance,
            'net_balance' => $netBalance
        ];
    }
}