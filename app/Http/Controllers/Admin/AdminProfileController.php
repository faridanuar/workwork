<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\User;
use App\EmployerRating;

use App\Employer;

use App\Http\Requests;

class AdminProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function profile(Request $request, $id, $business_name)
    {
        $company = Employer::findEmployer($id, $business_name)->firstOrFail();

        $user = $request->user();

        $logo = $company->business_logo;

        if($logo != "" && $logo != null && $logo != "/images/defaults/default.jpg")
        {
            $photo = $company->business_logo;

        }else{

            $photo = "/images/defaults/default.jpg";
        }

        $user = $request->user();

        $ratings = $company->ownRatings->count();

        $authorize = false;

        $rated = false;


        if($ratings === 0)
        {
            $ratings = 0;

            $average = 0;

        }else{

            $ratings;

            $average = $company->ownRatings->avg('rating');
        }

        if($user){
            $jobSeeker = $user->jobSeeker;

            if($jobSeeker)
            {
                $haveRating = EmployerRating::where('employer_id', $id)->where('job_seeker_id', $jobSeeker->id)->first();

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

        return view('admin.profile.admin_profile', compact('photo','company','authorize','rated','average','ratings'));
    }



    public function edit(Request $request)
    {
        $user = $request->user();

        $employer = $user->employer;

        return view('admin.profile.admin_profile_edit', compact('user','employer'));
    }



    public function update(Request $request)
    {
        $this->validate($request, [
                'business_name' => 'required',
                'business_category' => 'required',
            ]);
        
        $user = $request->user();

        $employer = $user->employer;

        // get new company name
        $newCompanyName = $request->business_name;
        // get current company name
        $currentCompanyName = $employer->business_name;

        /*
        // continue if new name is not the same as old name
        if($newCompanyName != $currentCompanyName)
        {
            // determine which rows to fetch
            $adverts = Advert::where('employer_id', '=', $user->employer->id);

            //MASS UPDATE existing advert's "avatar" column to database
            //$adverts->update([ 'business_name' => $newCompanyName ]);

            // fetch published adverts only
            $liveAds = $adverts->where('published', 1)->get();

            //fetch data from config.php
            $config = config('services.algolia');

            // provide index
            $index = $config['index'];

            // select algolia index/indice name
            $indexFromAlgolia = $search->index($index);

            // loop algolia object update for each row
            foreach($liveAds as $liveAd)
            {
                // update algolia existing object. Determine which by row id
                $object = $indexFromAlgolia->partialUpdateObject([
                    'business_name' => $newCompanyName,
                    'objectID' => $liveAd->id,
                ]);
            }
        }
        */

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

        flash('Your profile has been updated', 'success');

        return redirect()->route('admin_profile', [$employer->id,$employer->business_name]);
    }
}
