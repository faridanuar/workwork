<?php

namespace App\Http\Middleware;

use Closure;

use App\User;
use App\Employer;

use Carbon\Carbon;

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

        $todaysDate = Carbon::now();

        $endDate = $user->plan_ends_at;

        $daysLeft =  $todaysDate->diffInDays($endDate, false);

        if(!$user)
        {
            // redirect if user is not authorized
            return redirect()->guest('login');

        }elseif($user->type != "employer" || !$user->hasRole('employer')){

            return redirect()->back();

        }elseif($user->plan_ends_at === null){

            flash('You do not have an active plan, please purchase a new plan', 'info');

            return redirect('dashboard');

        }elseif($daysLeft < 0){

            flash('You do not have an active plan, please purchase a new plan', 'info');

            return redirect('dashboard');

        }elseif($user->employer === null){

            flash('You must create a profile first', 'info');

            return redirect('dashboard');
        }

        /*
        if (!$user->subscribed('main') && !$user->onGenericTrial() || !$user->hasRole("employer"))
        {
            flash('You have a company profile and your subscription must be active to use this feature', 'info');

            return redirect('/home');
        }
        */

        return $next($request);
    }
}
