<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Job_Seeker;
use App\Job_Seeker_Rating;
use App\Employer_Rating;
use App\Http\Requests;

class JobSeekerProfController extends Controller
{
    public function __construct()
    {
        $this->middleware('jobSeeker', ['except' => ['profileInfo','jobSeekerReview']]);
    }



    public function profileInfo(Request $request, $id, $user_id)
    {
    	$profileInfo = Job_Seeker::findJobSeeker($id, $user_id)->first();

        $ratings = $profileInfo->ownRating->count();

        $ratingSum = $profileInfo->ownRating->sum('rating');

        $user = $request->user();

        $rated = false;


        if($ratings === 0)
        {
            $average = 0;

        }else{

            $average = $profileInfo->ownRating->avg('rating');
        }


    	if($user)
        {
            $employer = $user->employer;

            if($employer)
            {
                $haveRating = Job_Seeker_Rating::where('job_seeker_id', $id)->where('employer_id', $employer->id)->first();

                if($haveRating === null){

                    $rated = false;

                }else{

                    if($haveRating->employer_id === $employer->id)
                    {
                        $rated = true;
                    } 
                }    
            }


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

    	return view('profiles.profile_info.profile', compact('user','profileInfo','authorize','rated','average', 'ratingSum'));

    }



    public function edit(Request $request)
    {
    	$user = $request->user();

    	$jobSeeker = $user->jobSeeker;

    	return view('profiles.profile_info.profile_edit', compact('user', 'jobSeeker'));
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

    	return redirect()->route('jobSeeker', [$jobSeeker->id,$jobSeeker->user_id]);
    }



    public function rate(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [

        'star' => 'required',
        'comment' => 'required|max:255',

        ]);

        $jobSeeker = $user->jobSeeker;

        $rating = new Employer_Rating;

        $rating->rating = $request->star;

        $rating->comment = $request->comment;

        $rating->postedBy = $user->name;

        $rating->jobSeeker()->associate($jobSeeker->id);

        $rating->employer()->associate($id);

        $rating->save();

        return redirect()->back();
    }



    public function jobSeekerReview($id, $user_id)
    {
        $jobSeeker = Job_Seeker::findJobSeeker($id, $user_id)->first();

        $userReviews = $jobSeeker->ownRating()->paginate(5);

        return view('profiles.profile_info.profile_reviews', compact('userReviews'));
    }
}
