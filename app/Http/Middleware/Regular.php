<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Regular
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
        if( in_array(auth()->user()->user_type, [0,1]))
            return $next($request);

        abort(403);
    }
}
