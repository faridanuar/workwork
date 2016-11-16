<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

use App\User;
use App\Advert;
use App\Category;
use App\Application;
use App\JobSeeker;
use App\JobSeekerRating;
use App\EmployerRating;


use App\Http\Requests;

class JobSeekerProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('jobSeeker', ['except' => ['profileInfo','jobSeekerReview']]);
    }



    public function preferCategory(Request $request)
    {
        if(count($request->user()->jobSeeker->categories) != 0)
        {
            return redirect('/');
        }

        $done = 1;
        $notDone = 1;

        return view('profiles.job_seeker.preferred_category',compact('done','notDone'));
    }



    public function getCategory(Request $request)
    {
        $this->validate($request, [

        'job_category' => 'required',

        ]);

        $user = $request->user();
        $user->ftu_level = 2;
        $user->save();

        $jobSeeker = $user->jobSeeker;

        $categories = $request->job_category;

        foreach($categories as $category)
        {
            $jobCategory = Category::where('name', $category)->get();
            $jobSeeker->categories()->attach($jobCategory);
        }

        flash('Welcome to WorkWork Job Seeker!','success');
        return redirect('/');
    }



    public function profileInfo(Request $request, $id)
    {
    	$profileInfo = JobSeeker::find($id);

        $ratings = $profileInfo->ownRatings->count();

        $avatar = $profileInfo->user->avatar;

        $user = $request->user();

        if($avatar != "" || $avatar != null){

            $photo = $avatar;

        }else{

            $photo = "/images/defaults/default.jpg";
        }

        if($ratings === 0)
        {
            $average = 0;

        }else{

            $average = $profileInfo->ownRatings->avg('rating');
        }

        if($user){
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

    	return view('profiles.job_seeker.profile', compact('photo','profileInfo','authorize','average','ratings'));
    }



    public function edit(Request $request)
    {
    	$user = $request->user();

    	$jobSeeker = $user->jobSeeker;

    	return view('profiles.job_seeker.profile_edit', compact('user', 'jobSeeker'));
    }



    public function update(Request $request)
    {
    	$user = $request->user();

    	$jobSeeker = $user->jobSeeker;

        if($user->contact != $request->contact)
        {
            $user->contact_verified = 0;
        }

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

    	return redirect()->route('jobSeeker', [$jobSeeker->id]);
    }



    public function rate(Request $request, $id, $business_name)
    {
        $user = $request->user();

        $this->validate($request, [

        'star' => 'required',
        'comment' => 'required|max:255',

        ]);

        $jobSeeker = $user->jobSeeker;

        $rating = new EmployerRating;

        $rating->rating = $request->star;

        $rating->comment = $request->comment;

        $rating->postedBy = $user->name;

        $rating->jobSeeker()->associate($jobSeeker->id);

        $rating->employer()->associate($id);

        $rating->save();

        flash('Thank you for your feedback', 'success');

        return redirect()->back();
    }



    public function jobSeekerReview($id)
    {
        $jobSeeker = JobSeeker::find($id);

        $userReviews = $jobSeeker->ownRatings()->paginate(5);

        return view('profiles.job_seeker.profile_reviews', compact('userReviews'));
    }

    public function allList(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;
        
        $requestInfos = $jobSeeker->applications()->where('job_seeker_id', $jobSeeker->id)->paginate(5);

        return view('profiles.job_seeker.all_applications', compact('jobSeeker','requestInfos'));
    }


    public function pendingList(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;
        
        $requestInfos = $jobSeeker->applications()->where('job_seeker_id', $jobSeeker->id)->where('status', 'PENDING')->paginate(5);

        return view('profiles.job_seeker.pending_application_list', compact('jobSeeker','requestInfos'));
    }



    public function rejectList(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;

        $rejectedInfos = $jobSeeker->applications()->where('job_seeker_id', $jobSeeker->id)->where('status', 'REJECTED')->paginate(5);

        return view('profiles.job_seeker.rejected_application_list', compact('jobSeeker','rejectedInfos'));
    }



    public function acceptList(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;
        
        $acceptedInfos = $jobSeeker->applications()->where('job_seeker_id', $jobSeeker->id)->where('status', 'ACCEPTED FOR INTERVIEW')->paginate(5);

        return view('profiles.job_seeker.accepted_application_list', compact('jobSeeker','acceptedInfos'));
    }



    public function appInfo($app_id)
    {
        $appInfo = Application::find($app_id);

        return view('profiles.job_seeker.application_info', compact('appInfo'));
    }



    protected function setAsViewed(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;

        $viewed = $request->viewed;

        if($viewed === 'accepted')
        {
            $requests = $jobSeeker->applications->where('status', 'ACCEPTED FOR INTERVIEW')->where('responded', 1)->where('viewed', 0);

        }elseif($viewed === 'rejected'){

            $requests = $jobSeeker->applications->where('status', 'REJECTED')->where('responded', 1)->where('viewed', 0);

        }else{

            $request = null;
        }

        foreach($requests as $requested)
        {
            $requested->viewed = 1;
            $requested->save();
        }
    }



    public function requestViewed(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;

        $id = $request->applicationID;

        $requests = $jobSeeker->applications->find($id);
        $requests->viewed = 1;
        $requests->save();
    }
}
