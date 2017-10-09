<?php

namespace App\Http\Middleware;

use Closure;

class RequireWelcome
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
        $volunteer = auth()->user()->volunteer;
        
        if (!$volunteer->hasLocation())
            return redirect()->route('welcome');

        return $next($request);
    }
}
