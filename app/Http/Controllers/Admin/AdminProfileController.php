<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Image;

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



    public function logo(Request $request)
    {
        $user = $request->user();

        $employer = $user->employer;

        $logo = $employer->business_logo;

        //check if photo path exist
        if($logo != "" && $logo != null && $logo != "/images/defaults/default.jpg")
        {

            $fileExist = true;

            $photo = $logo;

        }else{

            $fileExist = false;
            
            $photo = "/images/defaults/default.jpg";
        }

        return view('admin.profile.admin_company_logo', compact('photo', 'employer', 'fileExist'));
    }



    protected function uploadLogo(Request $request)
    {
        // store user's info in variable
        $employer = $request->user()->employer;

        $this->validate($request, [

            'photo' => 'required|mimes:jpg,jpeg,png,bmp' // validate image
        ]);

        // fetch photo
        $file = $request->file('photo');

        // set uploaded photo name into a unique name
        $name = time(). '-' .$file->getClientOriginalName();

        // set file directory for photo to be moved
        $path = "images/profile_images/logo";

        // compress, save and move the photo to the given path
        Image::make($file)->fit(200, 200)->save($path."/".$name);

        // get the new created photo directory path
        $pathURL = "/".$path."/".$name;

        // save the new photo directory path into the database
        $employer->business_logo = $pathURL;

        $employer->save();
    }



    public function remove(Request $request)
    {
        $employer = $request->user()->employer;

        $logo = $employer->business_logo;

        $user = $request->user();

        $employer->business_logo = "/images/defaults/default.jpg";

        $employer->save();

        flash('Your company logo has been removed', 'success');

        return redirect()->back();
    }
}
