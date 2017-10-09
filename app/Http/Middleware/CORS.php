<?php
namespace App\Http\Middleware;
use Closure;
use Response;
/**
 * Class Cors
 * @package App\Http\Middleware
 */
class Cors
{
    /**
     * Handle an incoming request.
     *
     * Please add header('Access-Control-Allow-Origin: http://example.com');
     * & header('Access-Control-Allow-Credentials: true');
     * at the top of your route file.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header('Access-Control-Allow-Origin: *');
        $headers = [
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, x-xsrf-token, X-CSRF-TOKEN, X-Auth-Token, Origin, Authorization'
        ];

        // if ($request->getMethod() == "OPTIONS") {
        //     return Response::make('OK', 200, $headers);
        // }

        $response = $next($request);
        foreach ($headers as $key => $value)
            $response->header($key, $value);
        return $response;
    }
}
