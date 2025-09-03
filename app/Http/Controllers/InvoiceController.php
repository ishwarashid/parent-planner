<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Paddle\Cashier;
use Exception;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if the user has a Paddle customer ID
        if (!$user->customer) {
            return redirect()->route('subscription.show')->with('error', 'Unable to access invoices.');
        }
        
        try {
            // Get the user's transactions (invoices) from Paddle
            $response = Cashier::api('GET', "transactions", [
                'customer_id' => $user->customer->paddle_id,
                'status' => 'completed',
                'per_page' => 50
            ]);
            
            $transactions = $response['data'] ?? [];
            
            // Format the transactions for display
            $invoices = collect($transactions)->map(function ($transaction) {
                // Log the transaction structure for debugging
                \Log::debug('Transaction structure', ['transaction' => $transaction]);
                
                return [
                    'id' => $transaction['id'] ?? 'Unknown',
                    'billed_at' => $transaction['billed_at'] ?? now(),
                    'total' => $transaction['details']['totals']['total'] ?? 0,
                    'currency' => $transaction['details']['totals']['currency_code'] ?? 'USD',
                    'items' => collect($transaction['details']['line_items'] ?? [])->map(function ($item) {
                        return [
                            'description' => $item['description'] ?? $item['product']['name'] ?? 'Unnamed Item',
                            'amount' => $item['unit_price']['amount'] ?? 0,
                            'currency' => $item['unit_price']['currency_code'] ?? 'USD',
                        ];
                    })->toArray(),
                ];
            })->toArray();
            
            return view('subscription.invoices.index', compact('invoices'));
        } catch (Exception $e) {
            \Log::error('Failed to load invoices: ' . $e->getMessage());
            return redirect()->route('subscription.show')->with('error', 'Failed to load invoices: ' . $e->getMessage());
        }
    }
    
    public function download($transactionId)
    {
        $user = Auth::user();
        
        // Check if the user has a Paddle customer ID
        if (!$user->customer) {
            return redirect()->route('subscription.show')->with('error', 'Unable to access invoice.');
        }
        
        try {
            // Get the transaction details from Paddle
            $response = Cashier::api('GET', "transactions/{$transactionId}");
            $transaction = $response['data'] ?? null;
            
            if (!$transaction) {
                return redirect()->route('subscription.invoices.index')->with('error', 'Invoice not found.');
            }
            
            // Check if the transaction belongs to the user
            if ($transaction['customer_id'] !== $user->customer->paddle_id) {
                return redirect()->route('subscription.invoices.index')->with('error', 'Unauthorized access to invoice.');
            }
            
            // Get the invoice PDF URL
            $invoiceUrl = $transaction['invoice_pdf'] ?? null;
            
            if (!$invoiceUrl) {
                return redirect()->route('subscription.invoices.index')->with('error', 'Invoice PDF not available.');
            }
            
            // Redirect to the PDF URL
            return redirect($invoiceUrl);
        } catch (Exception $e) {
            \Log::error('Failed to download invoice: ' . $e->getMessage());
            return redirect()->route('subscription.invoices.index')->with('error', 'Failed to download invoice: ' . $e->getMessage());
        }
    }
}