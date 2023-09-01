<?php

namespace App\Http\Middleware;

use Closure;

class DeviceIdChecker
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
        
        if( !$request->hasCookie('device_id') ){
            // dd('NO COOKIE', $request->cookie('device_id'));
            return redirect()->route('device-id-form.show');
        }
        // dd('HAS COOKIE');
        return $next($request);  
    }
}
