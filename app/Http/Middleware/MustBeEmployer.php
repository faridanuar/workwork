<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

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
        //store user info in a variable
        $user = $request->user();

        //check if user is not authenticated and does not have employer role
        if(!$user){

            // redirect if user is not authorized
            return redirect()->guest('login');

        }elseif($user->type != "employer" || !$user->hasRole('employer') || !$user->employer){

            return redirect('/');
        }

        // return to true if user is authorized
        return $next($request);
    }
}
