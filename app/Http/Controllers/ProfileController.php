<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employer;
use App\jobSeeker;
use App\Rating;
use App\Http\Requests;
use App\Http\Requests\EmployerRequest;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('employer', ['except' => ['profile']]);
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

        $authorize = "";

        $asEmployer = false;

        $rated = false;

        if($user)
        {   
            $jobSeeker = $user->jobSeeker;

            if($jobSeeker)
            {
                $rating = Rating::where('job_seeker_id', $jobSeeker->id)->firstOrFail();

                if($rating->job_seeker_id === $jobSeeker->id)
                {
                    $rated = true;
                }
            }


            $thisEmployer = $user->employer;

            if($thisEmployer){

                $asEmployer = true;

                if ($company->id === $thisEmployer->id)
                {
                    $authorize = true;

                }else{

                    $authorize = false;
                }
            }
        }

        return view('profiles.company', compact('company', 'authorize', 'rated', 'asEmployer'));

    }



    public function logo(Request $request)
    {
        $employer = $request->user()->employer()->first();

    	return view('profiles.logo', compact('employer'));
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
