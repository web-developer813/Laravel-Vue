<?php

namespace App\Http\Middleware;

use Closure;
use App\Forprofit;

class ForprofitsAdmin
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
        if(auth()->check()) {
            $forprofit = (session()->has('auth-forprofit'))
                ? session()->get('auth-forprofit')->fresh()
                : auth()->user()->forprofitsWithAdminAccess()->find($request->forprofit);
            
            if ($forprofit)
            {
                config()->set('authForprofit', $forprofit);
                view()->share('authForprofit', $forprofit);
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
