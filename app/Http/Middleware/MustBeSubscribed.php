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

        if(!$user){

            if ((!$user && $user->hasRole('employer') && $user->subscribed('main')) || (!($user && $user->hasRole('employer') && $user->onTrial())))
            {
                flash('Your must be registered as a company and your subscription must be active to use this feature', 'error');

                return redirect('/home');
            }
        }

        return $next($request);
    }
}
