<?php

namespace App\Providers;

use App\Models\Child;
use App\Policies\ChildPolicy;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Visitation;
use App\Policies\DocumentPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\VisitationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
