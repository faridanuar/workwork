<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\JobSeeker;

use App\Http\Requests;

class CreateProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('noProfile');
    }
    

    public function create(Request $request)
    {
        $user = $request->user();

        $done = 0;
        $notDone = -1;

        return view('profiles.profile_info.profile_create', compact('user','done','notDone'));
    }



    public function store(Request $request)
    {
        $user = $request->user();
        $user->update([
            // update user info
            'name' => $request->name,
            'contact' => $request->contact,
        ]);
        $user->ftu_level = 1;
        $user->save();

        // create a new user_id and fields and store it in jobseekers table
        $jobSeeker = $user->jobSeeker()->create([
            // 'column' => request->'field'
            'age' => $request->age,
            'location' => $request->location,
            'street' => $request->street,
            'city' => $request->city,
            'zip' => $request->zip,
            'state' => $request->state,
            'country' => $request->country, 
        ]);

        // set user_id in the row created in the Job_Seeker model using associate method
        $jobSeeker->user()->associate($user);

        // save user's job seeker profile info
        $jobSeeker->save();

        // redirect to home
        return redirect('/preferred-category');
    }
}
