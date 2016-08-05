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

        $role = "";

        //check if user has a role type, if not it redirect the user
        if(! $type)
        {
            return redirect('/choose');
        }


        if($user->employer)
        {
            $role = $user->employer;

        }else{

            $role = $user->jobSeeker;

        }

        //return user to home dashboard if user has a role type
        return view('home', compact('role', 'user'));
        
        
    }



    public function avatar(Request $request)
    {
        $user = $request->user();

        return view('auth.avatar', compact('user'));
    }


    public function uploadAvatar(Request $request)
    {

        $this->validate($request, [

            'photo' => 'required|mimes:jpg,jpeg,png,bmp' // validate image

        ]);

        $user = $request->user();

        $file = $request->file('photo');

        $name = time() . '-' .$file->getClientOriginalName();

        $file->move('profile_images/avatars', $name);

        $user->update([

                'avatar' => "/profile_images/avatars/{$name}"
        ]);

        $user->save();

        return back();
    }

}
