<?php

namespace App\Http\Middleware;

use Closure;

class AuthForprofit
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
        if (session()->has('auth-forprofit'))
        {
            $forprofit = session()->get('auth-forprofit')->fresh();
            view()->share('authForprofit', $forprofit);
        }
        return $next($request);
    }
}
