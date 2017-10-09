<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if (!auth()->user()->isAdmin())
        {
            flash('You do not have permission to access this page', 'error');
            return redirect()->route('home');
        }
        return $next($request);
    }
}
