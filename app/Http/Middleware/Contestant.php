<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Contestant
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
        // check contest type
            // if invited only, check invite or not
            //else redirect request page
        // check user access

        //if(!){
            //return redirect(url('/contest'));
        //}
        return $next($request);
    }
}
