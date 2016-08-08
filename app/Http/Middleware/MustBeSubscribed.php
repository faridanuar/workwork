<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class MustBeSubscribed
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

        if (($user && $user->hasRole('employer') && $user->subscribed('main')) OR ($user && $user->hasRole('employer') && $user->onTrial()))
        {

            return $next($request);

        }
        
        redirect('/');
    }
}
