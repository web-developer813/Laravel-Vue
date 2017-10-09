<?php

namespace App\Http\Middleware;

use Closure;
use App\Nonprofit;

class NonprofitsAdmin
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
        if (auth()->check()) {
            $nonprofit = (session()->has('auth-nonprofit'))
                ? session()->get('auth-nonprofit')->fresh()
                : auth()->user()->nonprofitsWithAdminAccess()->find($request->nonprofit);

            if ($nonprofit)
            {
                config()->set('authNonprofit', $nonprofit);
                view()->share('authNonprofit', $nonprofit);
            }

            else
            {
                $request->session()->forget('auth-nonprofit');
                $request->session()->forget('auth-forprofit');
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
