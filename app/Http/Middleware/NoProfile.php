<?php

namespace App\Http\Middleware;

use Closure;

use App\User;

class NoProfile
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
        $user = $request->user();

        if(!$user)
        {
            return redirect()->guest('login');

        }elseif($user->type === 'employer' || $user->jobSeeker || $user->employer){

            return redirect('/');

        }elseif(!$user->type){

            return redirect('/choose');
        }

        return $next($request);
    }
}
