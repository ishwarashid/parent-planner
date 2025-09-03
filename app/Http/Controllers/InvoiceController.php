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
            
            $invoices = $response['data'] ?? [];
            
            return view('subscription.invoices.index', compact('invoices'));
        } catch (Exception $e) {
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
            return redirect()->route('subscription.invoices.index')->with('error', 'Failed to download invoice: ' . $e->getMessage());
        }
    }
}