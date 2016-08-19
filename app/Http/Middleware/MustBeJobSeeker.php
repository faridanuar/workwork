<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class MustBeJobSeeker
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

        if (!$user)
        {
            return redirect()->guest('login');
        }

        if($user->type != "job_seeker")
        {
            return redirect('/');
        }

        return $next($request);

    }
}
