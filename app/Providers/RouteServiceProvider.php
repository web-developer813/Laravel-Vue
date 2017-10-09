<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();

        if (app()->environment('worker'))
            $this->mapWorkerRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'namespace' => $this->namespace,
            'middleware' => 'web',
        ], function ($router) {
            require base_path('routes/admin.php');
            require base_path('routes/app.php');
            require base_path('routes/forprofits.php');
            require base_path('routes/nonprofits.php');
            require base_path('routes/volunteers.php');
            require base_path('routes/website.php');
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'namespace' => $this->namespace,
            'middleware' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

    // worker routes
    protected function mapWorkerRoutes()
    {
        Route::group([
            'namespace' => $this->namespace,
            'middleware' => 'worker',
        ], function ($router) {
            require base_path('routes/worker.php');
        });
    }
}
