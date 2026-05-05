<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('manage-bookings', function ($user) {
            $role = $user ? (int)$user->role : 99;
            return $role === 0 || $role === 1;
        });

        Gate::define('manage-airports', function ($user) {
            $role = $user ? (int)$user->role : 99;
            return $role === 0 || $role === 2;
        });
    }
}
