<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gates
        Gate::define('is-admin', function ($user) {

            if ($user->role == 'admin'){
                return true;
            }

            return false;
        });
        Gate::define('is-user', function ($user) {

            if ($user->role == 'user'){
                return true;
            }

            return false;
        });
        Gate::define('is-user-or-admin', function ($user) {

            if ($user->role == 'user' or $user->role == 'admin' )
            {
                return true;
            }

            return false;
        });

    }
}
