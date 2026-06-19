<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(\App\Models\Proposal::class, \App\Policies\ProposalPolicy::class);
        Gate::policy(\App\Models\Lpj::class, \App\Policies\LpjPolicy::class);
    }
}
