<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helper;
use Auth;

class CheckIfIsOnDuty
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
        $helper = new Helper;
        
        if( !Auth::user()->isOnDuty($helper->getClarionDate(now())) ){
            Auth::logout(); 
            return redirect('/login')->with('error','Not on duty!');
        }

        return $next($request);
    }
}
