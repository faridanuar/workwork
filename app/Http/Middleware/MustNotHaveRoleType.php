<?php

namespace App\Http\Middleware;

use App\User;

use Closure;

class MustNotHaveRoleType
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

        if ( $user && !$user->type OR !$user->hasRole('employer') OR !$user->hasRole('job_seeker')){

            return $next($request);

        }else{

            return redirect()->guest('login');
        }
        
    }
}
