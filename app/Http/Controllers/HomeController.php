<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Braintree_ClientToken;
use \Braintree_Transaction;

use Image;

use Carbon\Carbon;

use App\User;
use App\Advert;

use App\Contracts\Search;

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

        $avatar = $user->avatar;

        if($avatar != "" || $avatar != null){

            //check if photo exist
            $exist = true;

        }else{

            $exist = false;
        }

        if($exist)
        {
            $photo = $avatar;

        }else{

            $photo = "/images/defaults/default.jpg";
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

            if($user->subscribed('main'))
            {
                $subscription = "Subscribed";

            }elseif($user->onGenericTrial()){

                $subscription = "Trial Plan";

            }else{

                $subscription = "Not Subscribed";
            }

        }elseif($user->jobSeeker){

            // set user role in a variable
            $role = $user->jobSeeker;

        }else{
        }


        // return user to home dashboard
        return view('home', compact('role','user','photo','subscription'));
    }


    /**
     * Show the upload avatar page.
     *
     */
    public function avatar(Request $request)
    {
        // store user info in variable
        $user = $request->user();

        $avatar = $user->avatar;

        if($avatar != "" || $avatar != null){

            $fileExist = true;

            $photo = $avatar;

        }else{

            $fileExist = false;

            $photo = "/images/defaults/default.jpg";
        }

        // display the upload page
        return view('auth.avatar', compact('user','photo','fileExist'));
    }


    /**
     * Store the uploaded image.
     *
     */
    protected function uploadAvatar(Request $request, Search $search)
    {   
        // store user's info in variable
        $user = $request->user();

        // validation function
        $this->validate($request, [

            // allow only this type of image
            'photo' => 'required|mimes:jpg,jpeg,png,bmp' 
        ]);

        //fetch data from config.php
        $config = config('services.algolia');

        // provide index
        $index = $config['index'];

        // store the uploaded file in a variable and fetch by paramName
        $file = $request->file('photo');

        // set the timestamp to the file name
        $name = time(). '-' .$file->getClientOriginalName();

        // provide a path
        $path = "images/profile_images/avatars/avatars";

        // provide path URl for Database
        $pathURL = "/".$path."/".$name;

        // use intervention facade to resize image then move/upload to dir
        Image::make($file)->fit(200, 200)->save($path."/".$name);

        // update user's file path url then save to database
        $user->update([ 'avatar' => $pathURL ]);

        $user->save();

        // run process if user is an employer
        if($user->employer)
        {
            // determine which rows to fetch
            $adverts = Advert::where('employer_id', '=',$user->employer->id);

            // fetch the rows
            $rows = $adverts->get();

            // select algolia index/indice name
            $indexFromAlgolia = $search->index($index);

            // loop algolia object update for each row
            foreach($rows as $row)
            {
                // update algolia existing object. Determine which by row id
                $object = $indexFromAlgolia->partialUpdateObject([
                    'avatar' => $pathURL,
                    'objectID' => $row->id,
                ]);
            }

            //MASS UPDATE existing advert's "avatar" column to database
            $adverts->update([ 'avatar' => $pathURL ]);
        }
}



    public function remove(Request $request, $avatar_id, Search $search)
    {
        // fetch user's info
        $user = $request->user();

        // find photo's row data using the "avatar_id"
        $thisPhoto = User::findOrFail($avatar_id);

        //fetch data from config.php
        $config = config('services.algolia');

        // provide index
        $index = $config['index'];

        //check IF job advert is own by user
        if(!$thisPhoto->avatarBy($user))
        {
            return $this->unauthorized($request);
        }

        // check IF avatar path url exist
        if($thisPhoto->avatar != "" || $thisPhoto->avatar != null){

            $exist = true;

        }else{

            $exist = false;
        }

        // run process IF photo path url exist/is true
        if($exist === true){

            //UPDATE advert's "avatar" column to null then SAVE changes to database
            $user->update([ 'avatar' => null ]);

            $user->save();

            // run process IF user is an employer
            if($user->employer)
            {
                // determine which rows to fetch
                $adverts = Advert::where('employer_id', '=',$user->employer->id);

                // fetch the rows
                $rows = $adverts->get();

                // select algolia index/indice name
                $indexFromAlgolia = $search->index($index);

                // provide path URl for Database
                $pathURL = "/images/defaults/default.jpg";

                // loop algolia object update for each row
                foreach($rows as $row)
                {
                    // update algolia existing object. Determine which by row id
                    $object = $indexFromAlgolia->partialUpdateObject([
                        'avatar' => $pathURL,
                        'objectID' => $row->id,
                    ]);
                }

                //MASS UPDATE existing advert's "avatar" column to database
                $adverts->update([ 'avatar' => $pathURL ]);
            }

            // flash message
            flash('Your photo has been successfully removed', 'success');

            return redirect()->back();

        }else{

            flash('Error, please try again', 'error');

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
