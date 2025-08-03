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
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\ProfessionalRegistrationController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\PublicProfessionalsController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return view('landing2');
});

Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('pricing');

Route::get('professionals', [PublicProfessionalsController::class, 'index'])->name('professionals.public.index');

Route::get('professional-register', [ProfessionalRegistrationController::class, 'create'])->name('professional.register');
Route::post('professional-register', [ProfessionalRegistrationController::class, 'store']);
Route::get('professional/registration-pending', function () {
    return view('auth.professional-registration-pending');
})->name('professional.registration.pending');

Route::get('/register-choice', function () {
    return view('auth.register-choice');
})->name('register.choice');

Route::middleware(['auth', 'verified', 'dashboard.access', 'admin', 'professional'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('visitations/api', [VisitationController::class, 'apiIndex'])->name('visitations.api');
    Route::resource('visitations', VisitationController::class)->only(['index', 'show']);
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('events', [CalendarController::class, 'store'])->name('events.store');
    Route::put('events/{event}', [CalendarController::class, 'update'])->name('events.update');
    Route::delete('events/{event}', [CalendarController::class, 'destroy'])->name('events.destroy');
});

Route::middleware(['auth', 'parent', 'verified', 'admin', 'professional'])->group(function () {
    Route::resource('children', ChildController::class);
    Route::resource('visitations', VisitationController::class)->except(['index', 'show']);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('documents', DocumentController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/visitations', [ReportController::class, 'generateVisitationReport'])->name('reports.visitations');
    Route::get('reports/expenses', [ReportController::class, 'generateExpenseReport'])->name('reports.expenses');
    Route::resource('invitations', InvitationController::class)->except(['show']);
    Route::post('invitations/{invitation}/resend', [InvitationController::class, 'resend'])->name('invitations.resend');

    Route::get('/billing', [SubscriptionController::class, 'billing'])->name('billing');
    Route::get('/billing-portal', [SubscriptionController::class, 'portal'])->name('billing.portal');
});

Route::get('invitation/{token}', [InvitationController::class, 'showAcceptForm'])->name('invitations.show')->middleware('guest');
Route::post('invitations/{invitation}/accept', [InvitationController::class, 'acceptInvitation'])->name('invitations.accept.process');
Route::post('invitations/{invitation}/reject', [InvitationController::class, 'rejectInvitation'])->name('invitations.reject.process');
Route::post('invitations/accept', [InvitationController::class, 'accept'])->name('invitations.accept')->middleware('guest');

Route::middleware(['auth', 'parent'])->group(function() {
    Route::get('/checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
});

Route::post('/stripe/webhook', [\Laravel\Cashier\Http\Controllers\WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

Route::middleware(['auth', 'admin', 'verified', 'professional'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'professional', 'verified', 'admin'])->prefix('professional')->name('professional.')->group(function () {
    Route::get('/dashboard', [ProfessionalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [ProfessionalController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfessionalController::class, 'update'])->name('profile.update');

    // Subscription routes for professionals
    Route::get('/pricing', [SubscriptionController::class, 'professionalPricing'])->name('pricing');
    Route::get('/billing', [SubscriptionController::class, 'billing'])->name('billing');
    Route::get('/checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
    Route::get('/billing-portal', [SubscriptionController::class, 'portal'])->name('billing.portal');
});

Route::middleware(['auth', 'professional'])->prefix('professional')->name('professional.')->group(function () {
    Route::get('/pricing', [SubscriptionController::class, 'professionalPricing'])->name('pricing');
    Route::get('/billing', [SubscriptionController::class, 'billing'])->name('billing');
});

Route::middleware(['auth', 'verified', 'admin', 'professional'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/professionals', [AdminController::class, 'professionals'])->name('admin.professionals.index');
    Route::get('/professionals/{professional}', [AdminController::class, 'showProfessional'])->name('admin.professionals.show');
    Route::post('/professionals/{professional}/approve', [AdminController::class, 'approveProfessional'])->name('admin.professionals.approve');
    Route::post('/professionals/{professional}/reject', [AdminController::class, 'rejectProfessional'])->name('admin.professionals.reject');
});
