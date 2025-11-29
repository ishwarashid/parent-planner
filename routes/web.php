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
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HelpController;
use App\Models\Blog;
use App\Models\LandingPageVideo;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Public Routes - No changes needed here
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $latestBlogs = Blog::where('published', true)
        ->orderBy('published_at', 'desc')
        ->take(3)
        ->get();
        
	$landingPageVideo = LandingPageVideo::active()->first();
    return view('landing2', compact('latestBlogs', 'landingPageVideo'));
})->name('home');

// Legal pages
Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/refund', function () {
    return view('legal.refund');
})->name('refund');

// Contact form route
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Help page route
Route::get('/help', [HelpController::class, 'index'])->name('help.index');
Route::get('/help/video/{id}/url', [HelpController::class, 'getVideoUrl'])->name('help.video.url');

// Video proxy route to bypass CORS
Route::get('/video-proxy/{filename}', [HelpController::class, 'proxyVideo'])->name('video.proxy');

// Blog routes
Route::get('/blogs', [App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blogs.show');

Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('pricing');
Route::get('/professional/pricing', [SubscriptionController::class, 'professionalPricing'])->name('professional.pricing');

Route::get('/professionals', [PublicProfessionalsController::class, 'index'])->name('professionals.public.index');

Route::get('/professionals/{professional}', [PublicProfessionalsController::class, 'show'])->name('professionals.public.show');

Route::get('professional-register', [ProfessionalRegistrationController::class, 'create'])->name('professional.register');
Route::post('professional-register', [ProfessionalRegistrationController::class, 'store']);
Route::get('professional/registration-pending', fn() => view('auth.professional-registration-pending'))->name('professional.registration.pending');
Route::get('/register-choice', fn() => view('auth.register-choice'))->name('register.choice');
Route::get('invitation/{token}', [InvitationController::class, 'showAcceptForm'])->name('invitations.show')->middleware('guest');
Route::post('invitations/accept', [InvitationController::class, 'accept'])->name('invitations.accept')->middleware('guest');
Route::post('/paddle/webhook', [Laravel\Paddle\Http\Controllers\WebhookController::class, '__invoke'])->name('cashier.webhook');
require __DIR__ . '/auth.php';

// Route for setting user timezone
Route::post('/set-timezone', function (\Illuminate\Http\Request $request) {
    $timezone = $request->input('timezone');

    // Validate timezone
    try {
        new DateTimeZone($timezone);
        session(['user_timezone' => $timezone]);
        return response()->json(['status' => 'success']);
    } catch (Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Invalid timezone'], 400);
    }
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Middleware Updates Start Here
|--------------------------------------------------------------------------
*/

// This group is now simplified. The complex authorization is handled by Policies within the controllers.
Route::middleware([
    'auth',
    'verified',
    'dashboard.access',
    'admin',
    'professional',
    'subscribed',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('visitations/api', [VisitationController::class, 'apiIndex'])->name('visitations.api');
    Route::resource('visitations', VisitationController::class)->only(['index', 'show']);
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('calendar/events', [CalendarController::class, 'getEventsForDateRange'])->name('calendar.events');
    Route::post('events', [CalendarController::class, 'store'])->name('events.store');
    Route::put('events/{event}', [CalendarController::class, 'update'])->name('events.update');
    Route::delete('events/{event}', [CalendarController::class, 'destroy'])->name('events.destroy');
    Route::patch('/events/{event}/status', [CalendarController::class, 'updateStatus'])->name('events.updateStatus');
});


// This was the most complex group. Now, it only needs to check for authentication.
// The `authorizeResource` method in each controller will check the user's permissions.
Route::middleware([
    'auth',
    'verified',
    'parent',
    'admin',
    'professional',
    'subscribed'
])->group(function () {
    Route::resource('children', ChildController::class);
    Route::resource('visitations', VisitationController::class)->except(['index', 'show']);
    Route::get('/expenses/balances', [ExpenseController::class, 'balanceSummary'])->name('expenses.balances');
    Route::post('/expenses/opening-balance', [ExpenseController::class, 'storeOpeningBalance'])->name('expenses.opening-balance.store');
    Route::post('/expenses/{expense}/confirm', [App\Http\Controllers\PaymentConfirmationController::class, 'store'])
        ->name('payments.confirm');
    Route::resource('expenses', ExpenseController::class);
    Route::resource('documents', DocumentController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/expenses', [ReportController::class, 'generateExpenseReport'])->name('reports.expenses');
    Route::get('reports/calendar', [ReportController::class, 'generateCalendarReport'])->name('reports.calendar');
    Route::resource('invitations', InvitationController::class)->except(['show']);
    Route::post('invitations/{invitation}/resend', [InvitationController::class, 'resend'])->name('invitations.resend');

    // Redirect old billing route to new subscription portal
    Route::get('/billing', function () {
        return redirect()->route('subscription.show');
    })->name('billing');
    
    // Redirect billing portal route to our new self-hosted subscription portal
    Route::get('/billing-portal', function () {
        return redirect()->route('subscription.show');
    })->name('billing.portal');

    // This route should now be part of the InvitationController logic, but leaving it as-is per your request.
    // The PermissionController is no longer used.
    Route::put('/users/{user}/permissions', [PermissionController::class, 'update'])->name('users.permissions.update');
});


// Unchanged, these are for invitation actions
Route::delete('invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy');
Route::post('invitations/{invitation}/accept', [InvitationController::class, 'acceptInvitation'])->name('invitations.accept.process');
Route::post('invitations/{invitation}/reject', [InvitationController::class, 'rejectInvitation'])->name('invitations.reject.process');
Route::get('/invitations/{invitation}/details', [InvitationController::class, 'details'])->name('invitations.details');


// The 'parent' check should be done inside the checkout method now (e.g., checking if the user is an account owner).
Route::middleware(['auth', 'parent'])->group(function () {
    Route::get('/checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
});


// Simplified group for the user's own profile. A user should always be able to manage their own profile.
Route::middleware(['auth', 'verified', 'admin', 'professional'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Simplified 'professional' routes. The controllers should now check for the 'Professional' role or specific permissions.
Route::middleware(['auth', 'verified', 'professional', 'admin'])->prefix('professional')->name('professional.')->group(function () {
    Route::get('/dashboard', [ProfessionalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [ProfessionalController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfessionalController::class, 'update'])->name('profile.update');

    // Subscription routes for professionals
    Route::get('/billing', function () {
        return redirect()->route('subscription.show');
    })->name('professional.billing');
    Route::get('/checkout', [SubscriptionController::class, 'checkout'])->name('professional.checkout');
    Route::get('/billing-portal', function () {
        return redirect()->route('subscription.show');
    })->name('professional.billing.portal');
});


// This second 'professional' group can likely be merged with the one above, but leaving it separate as requested.
Route::middleware(['auth', 'professional'])->prefix('professional')->name('professional.')->group(function () {
    Route::get('/pricing', [SubscriptionController::class, 'professionalPricing'])->name('pricing');
    Route::get('/billing', function () {
        return redirect()->route('subscription.show');
    })->name('billing');
});

// Self-hosted customer portal routes
Route::middleware(['auth'])->prefix('account/subscription')->group(function () {
    Route::get('/', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
    Route::post('/swap', [SubscriptionController::class, 'swap'])->name('subscription.swap');
    Route::get('/payment-method', [SubscriptionController::class, 'updatePaymentMethod'])->name('subscription.payment-method');
    
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('subscription.invoices.index');
    Route::get('/invoices/{transactionId}/download', [InvoiceController::class, 'download'])->name('subscription.invoices.download');
});

// Simplified 'admin' routes. The AdminController should check for the 'Admin' role or permissions.
Route::middleware(['auth', 'verified', 'admin', 'professional'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/professionals', [AdminController::class, 'professionals'])->name('admin.professionals.index');
    Route::get('/professionals/{professional}', [AdminController::class, 'showProfessional'])->name('admin.professionals.show');
    Route::post('/professionals/{professional}/approve', [AdminController::class, 'approveProfessional'])->name('admin.professionals.approve');
    Route::post('/professionals/{professional}/reject', [AdminController::class, 'rejectProfessional'])->name('admin.professionals.reject');
    
    // Users routes
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    
    // Blog routes
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class)->names([
        'index' => 'admin.blogs.index',
        'create' => 'admin.blogs.create',
        'store' => 'admin.blogs.store',
        'show' => 'admin.blogs.show',
        'edit' => 'admin.blogs.edit',
        'update' => 'admin.blogs.update',
        'destroy' => 'admin.blogs.destroy',
    ]);
    
    // Help Videos routes
    Route::resource('help-videos', \App\Http\Controllers\AdminHelpVideoController::class)->names([
        'index' => 'admin.help-videos.index',
        'create' => 'admin.help-videos.create',
        'store' => 'admin.help-videos.store',
        'show' => 'admin.help-videos.show',
        'edit' => 'admin.help-videos.edit',
        'update' => 'admin.help-videos.update',
        'destroy' => 'admin.help-videos.destroy',
    ]);

    // Landing Page Videos routes
    Route::resource('landing-page-videos', \App\Http\Controllers\AdminLandingPageVideoController::class)->names([
        'index' => 'admin.landing-page-videos.index',
        'create' => 'admin.landing-page-videos.create',
        'store' => 'admin.landing-page-videos.store',
        'show' => 'admin.landing-page-videos.show',
        'edit' => 'admin.landing-page-videos.edit',
        'update' => 'admin.landing-page-videos.update',
        'destroy' => 'admin.landing-page-videos.destroy',
    ]);
});
