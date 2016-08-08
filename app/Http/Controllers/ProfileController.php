<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employer;
use App\jobSeeker;
use App\Employer_Rating;
use App\Http\Requests;
use App\Http\Requests\EmployerRequest;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('employer', ['except' => ['profile', 'companyReview']]);
    }



    public function edit(Request $request)
    {
        $employer = $request->user()->employer()->first();

    	return view('profiles.company_edit', compact('employer'));
    }



    public function store(EmployerRequest $request)
    {
    	$user = $request->user();

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



    public function profile(Request $request, $id, $business_name)
    {
        $company = Employer::find($id);

        $user = $request->user();

        $authorize = false;

        $asEmployer = false;

        $rated = false;

        $ratings = $company->rating->count();

        $ratingSum = $company->rating->sum('rating');


        if($ratings === 0)
        {
            $average = 0;

        }else{

            $average = $company->rating->avg('rating');
        }


        if($user)
        {   
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

                $asEmployer = true;

                if ($company->id === $thisEmployer->id)
                {
                    $authorize = true;
                }
            }
        }

        return view('profiles.company', compact('company', 'authorize', 'rated', 'asEmployer', 'average', 'ratingSum'));
    }



    public function companyReview(Request $request, $id, $business_name)
    {
        $company = Employer::findEmployer($id, $business_name)->first();

        $userReviews = $company->rating()->paginate(1);

        return view('profiles.company_reviews', compact('company', 'userReviews'));

    }



    protected function uploadLogo(Request $request)
    {
        $this->validate($request, [

            'photo' => 'required|mimes:jpg,jpeg,png,bmp' // validate image

        ]);

    	$employer = $request->user()->employer()->first();

    	$file = $request->file('photo');

    	$name = time() . '-' .$file->getClientOriginalName();

    	$file->move('profile_images', $name);

    	$employer->update([

				'business_logo' => "/profile_images/{$name}"
    	]);

    	$employer->save();

    }
}
