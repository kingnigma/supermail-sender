<?php

namespace App\Providers;

use App\Models\ContactGroup;
use App\Models\EmailService;
use App\Policies\ContactGroupPolicy;
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

    protected $policies = [
        ContactGroup::class => ContactGroupPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
