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
        // store user info in variable
        $user = $request->user();

        // check if user is authorise and does not have type
        if($user && !$user->type){

            return $next($request);

        }else{

            return redirect()->guest('login');
        }
        
    }
}
