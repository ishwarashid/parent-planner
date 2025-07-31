<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\VisitationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return view('landing2');
});

Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('pricing');

Route::middleware(['auth', 'verified', 'subscribed'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('visitations/api', [VisitationController::class, 'apiIndex'])->name('visitations.api');
    Route::resource('children', ChildController::class);
    Route::resource('visitations', VisitationController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('documents', DocumentController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/visitations', [ReportController::class, 'generateVisitationReport'])->name('reports.visitations');
    Route::get('reports/expenses', [ReportController::class, 'generateExpenseReport'])->name('reports.expenses');

    Route::get('/billing', [SubscriptionController::class, 'billing'])->name('billing');
    Route::get('/billing-portal', [SubscriptionController::class, 'portal'])->name('billing.portal');
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
});

Route::post('/stripe/webhook', [\Laravel\Cashier\Http\Controllers\WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
