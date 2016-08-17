<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Advert;

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
        // fetch log in user data
        $user = $request->user();

        // fetch user's type of role
        $haveType = $user->type;


        // check if user has a role type, if not it redirect the user
        if(!$haveType || (!$user->employer && !$user->jobSeeker))
        {
            // check which type does the user have
            if($haveType  === "employer")
            {
                // redirect to create company profile page
                return redirect('/company/create');

            }elseif($haveType === "job_seeker"){

                // redirect to create profile page
                return redirect('/profile/create');

            }else{

                // redirect to choose type page
                return redirect('/choose');
            }
        }


        // fetch a row of record from user if exist
        if($user->employer)
        {
            // set user role in a variable
            $role = $user->employer;

            // fetch advert info that belongs to this user's employer id
            $adverts = Advert::where('employer_id', $role->id)->get();

        }elseif($user->jobSeeker){

            // set user role in a variable
            $role = $user->jobSeeker;

        }else{
        }


        // return user to home dashboard
        return view('home', compact('role', 'user'));
    }


    /**
     * Show the upload avatar page.
     *
     */
    public function avatar(Request $request)
    {
        // store user info in variable
        $user = $request->user();

        // display the upload page
        return view('auth.avatar', compact('user'));
    }


    /**
     * Store the uploaded image.
     *
     */
    public function uploadAvatar(Request $request)
    {   
        // validate function
        $this->validate($request, [

            // validate image
            'photo' => 'required|mimes:jpg,jpeg,png,bmp' 

        ]);

        // store user's info in variable
        $user = $request->user();

        // store the uploaded file in a variable and fetch by paramName
        $file = $request->file('photo');

        // set the timestamp to the file name
        $name = time() . '-' .$file->getClientOriginalName();

        // move the file to the directory in the server
        $file->move('profile_images/avatars', $name);

        // update user's file path form the database
        $user->update([

            'avatar' => $name,
        ]);

        //save changes made
        $user->save();

        // redirect to current page
        return back();
    }
}
