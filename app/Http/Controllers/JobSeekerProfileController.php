<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

use App\User;
use App\Advert;
use App\Category;
use App\Application;
use App\Job_Seeker;
use App\Job_Seeker_Rating;
use App\Employer_Rating;


use App\Http\Requests;

class JobSeekerProfileController extends Controller
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

        // check if user storing procedure is a success
        if($user){

            // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.welcomeJobSeeker', compact('user'), function ($m) use ($user) {

                $config = config('services.mailgun');

                $domain = $config['sender'];

                $recipient = 'farid@pocketpixel.com';

                $recipientName = $user->name;

                // set email sender stmp url and sender name
                $m->from($domain, 'WorkWork');

                // set email recepient and subject
                $m->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
            });
        }

        // redirect to home
        return redirect('/preferred-category');
    }



    public function preferCategory(Request $request)
    {
        return view('profiles.profile_info.preferred_category');
    }



    public function getCategory(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;

        $categories = $request->job_category;

        foreach($categories as $category)
        {
            $jobCategory = Category::where('name', $category)->get();
            $jobSeeker->categories()->attach($jobCategory);
        }

        return redirect('/recommended-jobs');
    }



    public function recommendedJobs(Request $request)
    {
        $user = $request->user();

        $interest = $user->jobSeeker->categories;

        dd($interest);

        $adverts = Advert::where('category', 'General Work')->orWhere('name', 'John')->take(5)->get();

        return view('profiles.profile_info.recommended_jobs', compact('adverts'));
    }



    public function profileInfo(Request $request, $id)
    {
    	$profileInfo = Job_Seeker::find($id);

        $ratings = $profileInfo->ownRating->count();

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

            $average = $profileInfo->ownRating->avg('rating');
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

    	return view('profiles.profile_info.profile', compact('photo','profileInfo','authorize','average','ratings'));
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



    public function jobSeekerReview($id)
    {
        $jobSeeker = Job_Seeker::find($id);

        $userReviews = $jobSeeker->ownRating()->paginate(5);

        return view('profiles.profile_info.profile_reviews', compact('userReviews'));
    }



    public function pendingList(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;
        
        $requestInfos = $jobSeeker->applications()->where('job_seeker_id', $jobSeeker->id)->where('status', 'PENDING')->paginate(5);

        return view('profiles.profile_info.application_pending_list', compact('jobSeeker','requestInfos'));
    }



    public function rejectList(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;

        $rejectedInfos = $jobSeeker->applications()->where('job_seeker_id', $jobSeeker->id)->where('status', 'REJECTED')->paginate(5);

        return view('profiles.profile_info.application_rejected_list', compact('jobSeeker','rejectedInfos'));
    }



    public function acceptList(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;
        
        $acceptedInfos = $jobSeeker->applications()->where('job_seeker_id', $jobSeeker->id)->where('status', 'ACCEPTED FOR INTERVIEW')->paginate(5);

        return view('profiles.profile_info.application_accepted_list', compact('jobSeeker','acceptedInfos'));
    }



    public function appInfo($app_id)
    {
        $appInfo = Application::find($app_id);

        return view('profiles.profile_info.application_info', compact('appInfo'));
    }



    protected function setAsViewed(Request $request)
    {
        $jobSeeker = $request->user()->jobSeeker;

        $requests = $jobSeeker->applications->where('status', 'ACCEPTED FOR INTERVIEW')->where('responded', 1)->where('viewed', 0);

        foreach($requests as $request)
        {
            $request->viewed = 1;
            $request->save();
        }
    }
}
