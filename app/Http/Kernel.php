<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        // for elastic load balancer
        \App\Http\Middleware\ValidProxies::class,
        \App\Http\Middleware\CORS::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
			\Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\AuthVolunteer::class,
            \App\Http\Middleware\AuthNonprofit::class,
            \App\Http\Middleware\AuthForprofit::class,
        ],

        'api' => [
            'throttle:120,1',
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\AuthVolunteer::class,
            \App\Http\Middleware\AuthNonprofit::class,
            \App\Http\Middleware\AuthForprofit::class,
        ],

        'worker' => [
            // 'throttle:120,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'cors' => \App\Http\Middleware\CORS::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'welcome' => \App\Http\Middleware\RequireWelcome::class,
        'nonprofitsAdmin' => \App\Http\Middleware\NonprofitsAdmin::class,
        'forprofitsAdmin' => \App\Http\Middleware\ForprofitsAdmin::class,
        'authNonprofit' => \App\Http\Middleware\AuthNonprofit::class,
        'authForprofit' => \App\Http\Middleware\AuthForprofit::class,
        'admin' => \App\Http\Middleware\Admin::class,
    ];
}
