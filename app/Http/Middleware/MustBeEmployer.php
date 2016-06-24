<?php

namespace App\Http\Middleware;

use Closure;

class MustBeEmployer
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
        //fetch user data with request from authentication
        $user = $request->user();

        if ( $user && $user->hasRole('employer')){

            return $next($request);

        }

        abort(404, 'You are not allowed.');
    }
}
