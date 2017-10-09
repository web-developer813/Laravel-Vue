<?php

namespace App\Http\Middleware;

use Closure;

class AuthVolunteer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check())
        {
            $volunteer = auth()->user()->volunteer;
            config()->set('authVolunteer', $volunteer);
            view()->share('authVolunteer', $volunteer);
        }
        return $next($request);
    }
}
