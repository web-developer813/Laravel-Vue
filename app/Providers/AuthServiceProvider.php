<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
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
        'App\Model' => 'App\Policies\ModelPolicy',
		'App\Follow' => 'App\Policies\FollowPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-nonprofit', function($user, $nonprofit) {
            return $user->isAdminForNonprofit($nonprofit);
        });

        Gate::define('admin-forprofit', function($user, $forprofit) {
            return $user->isAdminForForprofit($forprofit);
        });
    }
}
