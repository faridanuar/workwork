<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if(! $user->type == 'JobSeeker' || ! $user->type == 'employer'){

            if ( $user && $user->type == 'job_seeker'){

                    $user->assignRole('job_seeker');

            }elseif($user && $user->type == 'employer'){

                    $user->assignRole('employer');

            }else{

                 abort(401, 'You are not allowed.');

            }
        }

        return view('home');
    }

}
