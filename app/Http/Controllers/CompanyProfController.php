<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use File;

use App\Advert;
use App\Employer;
use App\job_Seeker;
use App\Job_Seeker_Rating;
use App\Employer_Rating;
use App\Application;

use App\Http\Requests;
use App\Http\Requests\EmployerRequest;

class CompanyProfController extends Controller
{

    public function __construct()
    {
        $this->middleware('employer', ['except' => ['profile', 'companyReview', 'create']]);
    }

    public function create(Request $request)
    {
        $user = $request->user();

        return view('profiles.company.company_create', compact('user'));
    }



    public function store(Request $request)
    {
        // store user info in variable
        $user = $request->user();

        // update user info
        $user->update([

            //update user info
            'name' => $request->name,
            'contact' => $request->contact,
        ]);

        //save user's info
        $user->save();

        // create a new user_id and fields and store it in jobseekers table
        $employer = $user->employer()->create([

            // 'column' => request->'field'
            'business_name' => $request->business_name,
            'business_category' => $request->business_category,
            'business_contact' => $request->business_contact,
            'business_website' => $request->business_website,
            'location' => $request->location,
            'street' => $request->street,
            'city' => $request->city,
            'zip' => $request->zip,
            'state' => $request->state,
            'company_intro' => $request->company_intro,
        ]);

        //set user_id in the Employer model using associate method
        $employer->user()->associate($user);

        //save changes
        $employer->save();

        //assign user a roles with permissions using "assignRole" method from hasRoles trait
        $user->assignRole('employer');

        // check if user storing procedure is a success
        if($user){

            // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.welcomeEmployer', compact('user'), function ($m) use ($user) {

                // set email sender stmp url and sender name
                $m->from('postmaster@sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org', 'WorkWork');

                // set email recepient and subject
                $m->to('farid@pocketpixel.com', $user->name)->subject('Welcome to WorkWork!');
            });
        }

        //set success flash message
        flash('Your company profile has been created. Welcome to WorkWork, Employer!', 'success');

        // redirect to dashboard
        return redirect('/dashboard');
    }



    public function profile(Request $request, $id, $business_name)
    {
        $company = Employer::findEmployer($id, $business_name)->first();

        $exist = File::exists('.'.$company->business_logo);

        $user = $request->user();

        if($exist)
        {
            $photo = $company->business_logo;

        }else{

            $photo = "/images/profile_images/defaults/default.jpg";
        }

        $user = $request->user();

        $ratings = $company->ownRating->count();

        $authorize = false;

        $rated = false;


        if($ratings === 0)
        {
            $ratings = 0;

            $average = 0;

        }else{

            $average = $company->ownRating->avg('rating');
        }

        if($user){

            $jobSeeker = $user->jobSeeker;

            if($jobSeeker)
            {
                $haveRating = Employer_Rating::where('employer_id', $id)->where('job_seeker_id', $jobSeeker->id)->first();

                if($haveRating === null){

                    $rated = false;

                }else{

                    if($haveRating->job_seeker_id === $jobSeeker->id)
                    {
                        $rated = true;
                    } 
                }    
            }


            $thisEmployer = $user->employer;

            if($thisEmployer){

                if ($company->id === $thisEmployer->id)
                {
                    $authorize = true;
                }
            }
        }

        return view('profiles.company.company', compact('photo','company','authorize','rated','average','ratings'));
    }



    public function edit(Request $request)
    {
        $user = $request->user();

        $employer = $user->employer;

        return view('profiles.company.company_edit', compact('user','employer'));
    }



    public function update(EmployerRequest $request)
    {
        $user = $request->user();

        // update user info
        $user->update([

            //update user info
            'name' => $request->name,
            'contact' => $request->contact,
        ]);

        //save user's info
        $user->save();

        $employer = $user->employer()->first();

        $employer->update([

                'business_name' => $request->business_name,
                'business_category' => $request->business_category,
                'business_contact' => $request->business_contact,
                'business_website' => $request->business_website,
                'location' => $request->location,
                'street' => $request->street,
                'city' => $request->city,
                'zip' => $request->zip,
                'state' => $request->state,
                'company_intro' => $request->company_intro,
        ]);

        $employer->save();

        flash('Your profile has been updated', 'success');

        return redirect()->route('company', [$employer->id,$employer->business_name]);
    }



    public function logo(Request $request)
    {
        $employer = $request->user()->employer;

        $logo = $employer->business_logo;

        $path = '.'.$logo;

        //check if photo path exist
        if($logo != "" || $logo != null){

            //check if file exists
            $exist = File::exists($path);

        }else{

            $exist = false;
        }

        if($exist === true)
        {
            $fileExist = true;
            $photo = $logo;

        }else{

            $fileExist = false;
            $photo = "/images/profile_images/defaults/default.jpg";
        }

        return view('profiles.company.logo', compact('photo','employer','fileExist'));
    }



    protected function uploadLogo(Request $request)
    {
        // store user's info in variable
        $employer = $request->user()->employer()->first();

        //fetch if previous photo name
        $logo = $employer->business_logo;

        $path = '.'.$logo;

        //check if photo path exist
        if($logo != "" || $logo != null){

            //check if previous photo exists
            $exist = File::exists($path);

        }else{

            $exist = false;
        }

        //if file exists
        if($exist === true)
        {
            //delete file
           unlink($path);

           //remove path name from database
           $employer->update(['business_logo' => null]);

           $employer->save();

        }else{

        }

        $this->validate($request, [

            'photo' => 'required|mimes:jpg,jpeg,png,bmp' // validate image

        ]);

    	$file = $request->file('photo');

    	$name = time() . '-' .$file->getClientOriginalName();

    	$file->move('images/profile_images/logo', $name);

    	$employer->update([

				'business_logo' => "/images/profile_images/logo/{$name}",
    	]);

    	$employer->save();
    }



    public function destroy(Request $request, $logo_id)
    {
        $employer = Employer::findOrFail($logo_id);

        $logo = $employer->business_logo;

        $path = '.'.$logo;

        $user = $request->user();

        //check if job advert is own by user
        if(!$employer->logoBy($user))
        {
            return $this->unauthorized($request);
        }

        if($logo != "" || $logo != null){

            $exist = File::exists($path);

        }else{

            $exist = false;
        }

        if($exist === true){

            if (unlink($path))
            {
                $employer->update(['business_logo' => null]);

                $employer->save();

                flash('Your photo has been successfully removed', 'success');

                return redirect()->back();
            }

        }else{

            flash('Error, please try again', 'error');

            return redirect()->back();
        }
    }



    public function rate(Request $request, $id)
    {
        $user = $request->user();

        $this->validate($request, [

        'star' => 'required',
        'comment' => 'required|max:255',

        ]);

        $employer = $user->employer;

        $rating = new Job_Seeker_Rating;

        $rating->rating = $request->star;

        $rating->comment = $request->comment;

        $rating->postedBy = $user->name;

        $rating->jobSeeker()->associate($id);

        $rating->employer()->associate($employer->id);

        $rating->save();

        flash('Thank you for your feedback', 'success');

        return redirect()->back();
    }


    public function companyReview($id, $business_name)
    {
        $company = Employer::findEmployer($id, $business_name)->first();

        $userReviews = $company->ownRating()->paginate(5);

        return view('profiles.company.company_reviews', compact('company', 'userReviews'));
    }



    public function myAdvert(Request $request)
    {
        $employerID = $request->user()->employer->id;

        $myAdverts = Advert::where('employer_id', $employerID)->orderBy('updated_at', 'desc')
                ->get();

        return view('profiles.company.company_adverts', compact('myAdverts'));
    }



    public function jobRequest($id)
    {
        $requestInfos = Application::where('advert_id', $id)->where('status', 'PENDING')->paginate(5);

        return view('profiles.company.company_requests', compact('requestInfos', 'id'));
    }



    public function rejected($id)
    {
        $requestInfos = Application::where('advert_id', $id)->where('status', 'REJECTED')->paginate(5);

        return view('profiles.company.company_requests_rejected', compact('requestInfos', 'id'));
    }



    public function inReview($id)
    {
        $requestInfos = Application::where('advert_id', $id)->where('status', 'ACCEPTED FOR INTERVIEW')->paginate(5);

        return view('profiles.company.company_requests_inReview', compact('requestInfos', 'id'));
    }



    public function response(Request $request, $id)
    {
        $application = Application::find($id);

        $application->update([

            'status' => $request->status,
            'employer_reason' => $request->comment,
            'responded' => 1,
        ]);

        $application->save();

        // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
        Mail::send('mail.applicationNotification', compact('application'), function ($m) use ($application) {

            // set email sender stmp url and sender name
            $m->from('postmaster@sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org', 'WorkWork');

            // set email recepient and subject
            $m->to('farid@pocketpixel.com', $application->jobSeeker->user->name)->subject('Application Notification');
        });

        return redirect()->back();
    }



    public function appliedProfile(Request $request, $id, $role_id)
    {
        $advert = Advert::find($id);

        $user = $request->user();

        //check if job advert is own by user
        if(!$advert->ownedBy($user))
        {
            return $this->unauthorized($request);
        }

        $profileInfo = Job_Seeker::find($role_id);

        $avatar = $profileInfo->user->avatar;

        $path = '.'.$avatar;

        $exist = File::exists($path);

        if($avatar != "" || $avatar != null){

            $exist = File::exists($path);

        }else{

            $exist = false;
        }

        if($exist)
        {
            $photo = $profileInfo->user->avatar;

        }else{

            $photo = "/profile_images/defaults/default.jpg";
        }

        $responded = Application::where('advert_id', $id)->where('job_seeker_id', $role_id)->first()->responded;

        $ratings = $profileInfo->ownRating->count();

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

            $haveRating = Job_Seeker_Rating::where('job_seeker_id', $role_id)->where('employer_id', $employer->id)->first();

            if($haveRating === null){

                $rated = false;

            }else{

                if($haveRating->employer_id === $employer->id)
                {
                    $rated = true;
                } 
            }    
        }

        return view('profiles.company.request_applied', compact('photo','profileInfo','rated','average','ratings','responded'));
    }

    /**
     * Check if user is authorized
     *
     * @param $request
     */
    protected function unauthorized(Request $request)
    {
        if($request->ajax())
        {
            return response(['message' => 'No!'], 403);
        }

        abort(403, 'Unauthorized action.');
    }
}
