<?php

namespace App\Providers;

use App\Models\Child;
use App\Policies\ChildPolicy;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Visitation;
use App\Policies\DocumentPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\InvitationPolicy;
use App\Policies\VisitationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Child::class => ChildPolicy::class,
        Visitation::class => VisitationPolicy::class,
        Document::class => DocumentPolicy::class,
        Expense::class => ExpensePolicy::class,
        Invitation::class => InvitationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->is_admin) {
                return true;
            }
        });

        Gate::define('billing.view', function ($user) {
            return $user->hasPermissionTo('billing.view');
        });
    }
}
