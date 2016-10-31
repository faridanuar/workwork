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

        //check if user is not authenticated and does not have job seeker role
        if (!$user)
        {
            // redirect if user is not authorized
            return redirect()->guest('login');

        }elseif($user->type != "job_seeker" || !$user->hasRole('job_seeker') || !$user->jobSeeker){

            // redirect to home
            return redirect('/');
        }

        // return to true if user is authorized
        return $next($request);
    }
}
