<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;

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

        //check if photo exist
        $exist = File::exists('.'.$user->avatar);


        if($exist)
        {
            $photo = $user->avatar;

        }else{

            $photo = "/images/profile_images/defaults/default.jpg";
        }


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
        return view('home', compact('role','user','photo'));
    }


    /**
     * Show the upload avatar page.
     *
     */
    public function avatar(Request $request)
    {
        // store user info in variable
        $user = $request->user();

        $exist = File::exists('.'.$user->avatar);

        if($exist)
        {
            $fileExist = true;

            $photo = $user->avatar;

        }else{

            $fileExist = false;

            $photo = "/images/profile_images/defaults/default.jpg";
        }

        // display the upload page
        return view('auth.avatar', compact('user','photo','fileExist'));
    }


    /**
     * Store the uploaded image.
     *
     */
    public function uploadAvatar(Request $request)
    {   
        // store user's info in variable
        $user = $request->user();

        //fetch if previous photo name
        $photo = $user->avatar;

        //check if previous photo exists
        $exist = File::exists($photo);

        //if previous photo exists then delete
        if($exist)
        {
           unlink($photo);

           $user->update(['avatar' => null]);

           $user->save();

        }else{

        }

        // validate function
        $this->validate($request, [

            // validate image
            'photo' => 'required|mimes:jpg,jpeg,png,bmp' 
        ]);

        // store the uploaded file in a variable and fetch by paramName
        $file = $request->file('photo');

        // set the timestamp to the file name
        $name = time() . '-' .$file->getClientOriginalName();

        // move the file to the directory in the server
        $file->move('images/profile_images/avatars', $name);

        // update user's file path form the database
        $user->update([

            'avatar' => "/images/profile_images/avatars/{$name}",
        ]);

        //save changes made
        $user->save();
        
        // redirect to current page
        return back();
    }



    public function destroy(Request $request, $avatar_id)
    {
        $user = $request->user();

        $avatar = User::findOrFail($avatar_id);

        $photo = $avatar->avatar;


        //check if job advert is own by user
        if(!$avatar->ownedBy($user))
        {
            return $this->unauthorized($request);
        }

        $exist = File::exists($photo);


        if($exist){

            if (unlink($photo))
            {
                $user->update(['avatar' => null]);

                $user->save();

                flash('Your photo is successfully removed', 'success');

                return redirect()->back();
            }

        }else{

            flash('There are no photos to be removed', 'error');

            return redirect()->back();
        }
    }



    protected function unauthorized(Request $request)
    {
        if($request->ajax())
        {
            return response(['message' => 'No!'], 403);
        }

        abort(403, 'Unauthorized action.');
    }
}
