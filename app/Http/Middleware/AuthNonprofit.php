<?php

namespace App\Http\Middleware;

use Closure;

class AuthNonprofit
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
        if (session()->has('auth-nonprofit'))
        {
            $nonprofit = session()->get('auth-nonprofit')->fresh();
            view()->share('authNonprofit', $nonprofit);
        }
        return $next($request);
    }
}
