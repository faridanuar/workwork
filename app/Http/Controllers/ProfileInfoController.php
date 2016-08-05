<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Job_Seeker;
use App\Rating;
use App\Http\Requests;

class ProfileInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('jobSeeker', ['except' => ['profileInfo']]);
    }



    public function profileInfo(Request $request, $id, $name)
    {
    	$profileInfo = Job_Seeker::find($id);

    	$user = $request->user();



    	if($user)
        {
        	$jobSeeker = $user->jobSeeker;

        	if($jobSeeker)
        	{
                if($jobSeeker->id === $profileInfo->id)
                {
                    $authorize = true;

                }else{

                    $authorize = false;
                }
            }
        }

    	return view('profiles.profile_info', compact('user','authorize','jobSeeker'));

    }



    public function edit(Request $request)
    {
    	$user = $request->user();

    	$jobSeeker = $user->jobSeeker;

    	return view('profiles.profile_info_edit', compact('user', 'jobSeeker'));
    }



    public function store(Request $request)
    {
    	$user = $request->user();

    	$jobSeeker = $user->jobSeeker;

    	$user->update([
    		'name' => $request->name,
    		'contact' => $request->contact,
    	]);

    	$jobSeeker->update([

                'age' => $request->age,
                'location' => $request->location,
				'street' => $request->street,
				'city' => $request->city,
				'zip' => $request->zip,
				'state' => $request->state,
				'country' => $request->country,
    	]);

    	$jobSeeker->save();

    	$user->save();

    	// set flash attribute and key. example --> flash('success message', 'flash_message_level')
		flash('Your profile has been updated', 'success');

    	return redirect()->route('jobSeeker', [$jobSeeker->id,$user->name]);
    }



    public function rate(Request $request, $id)
    {
        $this->validate($request, [

        'star' => 'required',
        'comment' => 'required|max:255',
        ]);


        $jobSeeker = $request->user()->jobSeeker;

        $rating = new Rating;

        $rating->rating = $request->star;

        $rating->comment = $request->comment;

        $rating->jobSeeker()->associate($jobSeeker->id);

        $rating->employer()->associate($id);

        $rating->save();
    }
}
