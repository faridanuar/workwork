<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

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



    public function create(Request $request)
    {
        $user = $request->user();

        return view('profiles.profile_info.profile_create', compact('user'));
    }



    public function store(Request $request)
    {
        $user = $request->user();

        $user->update([
            // update user info
            'name' => $request->name,
            'contact' => $request->contact,
        ]);

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

        // assign role "job_seeker" permissions with "assignRole" method from hasRoles trait
        $user->assignRole('job_seeker');

        // check if user storing procedure is a success
        if($user){

            // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.welcomeJobSeeker', compact('user'), function ($m) use ($user) {

                // set email sender stmp url and sender name
                $m->from('postmaster@sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org', 'WorkWork');

                // set email recepient and subject
                $m->to('farid@pocketpixel.com', $user->name)->subject('Welcome to WorkWork!');
            });
        }

        // set flash attribute and key. example --> flash('success message', 'flash_message_level')
        flash('Your profile has been updated', 'success');

        // redirect to home
        return redirect('/home');
    }



    public function profileInfo(Request $request, $id, $user_id)
    {
    	$profileInfo = Job_Seeker::findJobSeeker($id, $user_id)->first();

        $ratings = $profileInfo->ownRating->count();

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

    	return view('profiles.profile_info.profile', compact('user','profileInfo','authorize','rated','average', 'ratings'));

    }



    public function edit(Request $request)
    {
    	$user = $request->user();

    	$jobSeeker = $user->jobSeeker;

    	return view('profiles.profile_info.profile_edit', compact('user', 'jobSeeker'));
    }



    public function update(Request $request)
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

        flash('Thank you for your feedback', 'success');

        return redirect()->back();
    }



    public function jobSeekerReview($id, $user_id)
    {
        $jobSeeker = Job_Seeker::findJobSeeker($id, $user_id)->first();

        $userReviews = $jobSeeker->ownRating()->paginate(5);

        return view('profiles.profile_info.profile_reviews', compact('userReviews'));
    }
}
