<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->api_password !== env('API_PASSWORD')){
            return response()->json(['msg'=>'API Password Is Wrong If You want To Access To Api Pleas Write Correct Password']);
        }
        return $next($request);
    }
}
