<?php

namespace App\Http\Middleware;

use Closure;

class OutletChecker
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
        if( !auth()->user()->outlet_id ){
            return redirect()->route('outlets');
        }
        return $next($request); 
    }
}
