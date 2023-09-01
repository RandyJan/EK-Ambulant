<?php

namespace App\Http\Middleware;

use Closure;

class DeviceChecker
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
        if( !auth()->user()->device_no ){
            return redirect()->route('devices'); 
        }
        return $next($request);
    }
}
