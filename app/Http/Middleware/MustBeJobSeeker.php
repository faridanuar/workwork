<?php

namespace App\Http\Middleware;

use Closure;

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
        $user = $request->user();

        if ($user && $user->name == 'daniel dan'){

            return $next($request);

        }

        abort(404, 'You are not allowed.');

        
    }
}
