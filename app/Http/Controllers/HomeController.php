<?php

namespace App\Http\Controllers;

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
        //fetch log in user data
        $user = $request->user();

        //fetch user's type of role
        $type = $user->type;
        

        //check if user has a role type, if not it redirect the user
        if(! $type)
        {
            return redirect('/choose');
        }

        
        //return user to home dashboard if user has a role type
        return view('home');
        


    }

}
