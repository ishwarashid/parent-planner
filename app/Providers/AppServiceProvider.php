<?php

namespace App\Providers;

use App\Models\Child;
use App\Models\Event;
use App\Models\Visitation;
use App\Observers\EventObserver;
use App\Observers\VisitationObserver;
use App\Policies\ChildPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate::policy(Child::class, ChildPolicy::class);
        
        // Register model observers
        Event::observe(EventObserver::class);
        Visitation::observe(VisitationObserver::class);
    }
}
