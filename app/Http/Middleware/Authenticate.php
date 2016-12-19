<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use User;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {

            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }

        }elseif(Auth::user()->type != null || Auth::user()->type != ""){

            if(Auth::user()->type === "employer" && !Auth::user()->employer)
            {
                return redirect('/company/create');
                
            }elseif(Auth::user()->type === "job_seeker" && !Auth::user()->jobSeeker){

                return redirect('/profile/create');
            }

        }elseif(!Auth::user()->type){
            
            return redirect('/choose');
        }

        return $next($request);
    }
}
