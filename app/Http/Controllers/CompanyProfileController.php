<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Mail;
use Image;
use Services_Twilio;

use Carbon\Carbon;

use \Braintree_ClientToken;
use \Braintree_Transaction;

use App\Advert;
use App\Employer;
use App\JobSeeker;
use App\JobSeekerRating;
use App\EmployerRating;
use App\Application;

use App\Contracts\Search;

use App\Http\Requests;
use App\Http\Requests\EmployerRequest;

class CompanyProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('employer', ['except' => ['profile', 'companyReview']]);
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

        return view('profiles.company.company', compact('photo','company','authorize','rated','average','ratings'));
    }



    public function edit(Request $request)
    {
        $user = $request->user();

        $employer = $user->employer;

        return view('profiles.company.company_edit', compact('user','employer'));
    }



    public function update(EmployerRequest $request, Search $search)
    {
        $user = $request->user();

        $employer = $user->employer;

        // get new company name
        $newCompanyName = $request->business_name;

        // get current company name
        $currentCompanyName = $employer->business_name;

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

        //check if photo path exist
        if($logo != "" && $logo != null && $logo != "/images/defaults/default.jpg")
        {

            $fileExist = true;

            $photo = $logo;

        }else{

            $fileExist = false;
            
            $photo = "/images/defaults/default.jpg";
        }

        return view('profiles.company.logo', compact('photo','employer','fileExist'));
    }



    protected function uploadLogo(Request $request, Search $search)
    {
        // store user's info in variable
        $employer = $request->user()->employer()->first();

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

        // fetch employer's created adverts
        $adverts = Advert::where('employer_id', '=',$employer->id);

        // filter and fetch live/published adverts only
        $liveAdverts = $adverts->where('published', 1)->get();

        //fetch data from config.php
        $config = config('services.algolia');

        // provide index
        $index = $config['index'];

        // select algolia index/indice name
        $indexFromAlgolia = $search->index($index);

        // loop algolia object update for each row
        foreach($liveAdverts as $liveAdvert)
        {
            // update algolia existing object. Determine which by row id
            $object = $indexFromAlgolia->partialUpdateObject([
                'logo' => $pathURL,
                'objectID' => $liveAdvert->id,
            ]);
        }
    }



    public function remove(Request $request, $logo_id, Search $search)
    {
        $employer = Employer::findOrFail($logo_id);

        $logo = $employer->business_logo;

        $user = $request->user();

        //check if job advert is own by user
        if(!$employer->logoBy($user))
        {
            return $this->unauthorized($request);
        }

        $employer->business_logo = "/images/defaults/default.jpg";

        $employer->save();

        // determine which rows to fetch
        $adverts = Advert::where('employer_id', '=',$employer->id);

        // provide path URl for Database
        $pathURL = "/images/defaults/default.jpg";

        // fetch published adverts only
        $liveAdverts = $adverts->where('published', 1)->get();

        //fetch data from config.php
        $config = config('services.algolia');

        // provide index
        $index = $config['index'];

        // select algolia index/indice name
        $indexFromAlgolia = $search->index($index);

        // loop algolia object update for each row
        foreach($liveAdverts as $liveAdvert)
        {
            // update algolia existing object. Determine which by row id
            $object = $indexFromAlgolia->partialUpdateObject([
                'logo' => $pathURL,
                'objectID' => $liveAdvert->id,
            ]);
        }

        flash('Your photo has been successfully removed', 'success');

        return redirect()->back();
    }



    public function rate(Request $request, $id)
    {
        $user = $request->user();

        $this->validate($request, [

        'star' => 'required',
        'comment' => 'required|max:255',

        ]);

        $employer = $user->employer;

        $rating = new JobSeekerRating;

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

        $userReviews = $company->ownRatings()->paginate(5);

        return view('profiles.company.company_reviews', compact('company', 'userReviews'));
    }



    public function myAdvert(Request $request)
    {
        $user = $request->user();

        if(!$user->employer)
        {
            flash('You need a profile to create an advert', 'info');

            return redirect('/company/create');
        }

        $employerID = $user->employer->id;

        $myAdverts = Advert::where('employer_id', $employerID)
                        ->orderBy('published', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('profiles.company.company_adverts', compact('myAdverts'));
    }



    public function allList($id)
    {
        $advert = Advert::find($id);

        $currentPlan = $advert->current_plan;

        switch ($currentPlan)
        {
            case "Free":
                $count = 3;
                break;

            case "Trial":
                $count = 10;    
                break;

            default: 
                $count = 1; 
        }

        if($currentPlan != '1_Month_Plan' && $currentPlan != '2_Month_Plan')
        {
            $limitedRequest = Application::where('advert_id', $id)->take($count);

            $allInfos = $limitedRequest->get();

        }else{

            $allInfos = Application::where('advert_id', $id)->paginate(5);
        }

        return view('profiles.company.all_job_requests', compact('currentPlan','allInfos', 'id', 'advert'));
    }



    public function pendingList($id)
    {
        $advert = Advert::find($id);

        $currentPlan = $advert->current_plan;

        switch ($currentPlan)
        {
            case "Free":
                $count = 3;
                break;

            case "Trial":
                $count = 10;    
                break;

            default:
                $count = 1;    
        }

        if($currentPlan != '1_Month_Plan' && $currentPlan != '2_Month_Plan')
        {
            $limitedRequest = Application::where('advert_id', $id)->take($count);

            $requestInfos = $limitedRequest->get();

        }else{

            $requestInfos = Application::where('advert_id', $id)->where('status', 'PENDING')->paginate(5);
        }
        
        return view('profiles.company.pending_job_requests', compact('currentPlan','requestInfos', 'id', 'advert'));
    }



    public function rejectedList($id)
    {
        $advert = Advert::find($id);

        $rejectedInfos = Application::where('advert_id', $id)->where('status', 'REJECTED')->paginate(5);

        return view('profiles.company.rejected_job_requests', compact('rejectedInfos', 'id', 'advert'));
    }



    public function acceptedList($id)
    {
        $advert = Advert::find($id);

        $acceptedInfos = Application::where('advert_id', $id)->where('status', 'ACCEPTED FOR INTERVIEW')->paginate(5);

        return view('profiles.company.accepted_job_requests', compact('acceptedInfos', 'id', 'advert'));
    }



    public function response(Request $request, $id)
    {
        $application = Application::find($id);

        $recipientName = $application->jobSeeker->user->name;

        $contact = $application->jobSeeker->user->contact;

        $status = $request->status;

        if($status != "REJECTED")
        {
            $this->validate($request, [
                'status' => 'required|max:50',
                'arrangement' => 'required',
            ]);

            $comment = $request->arrangement;

        }else{

            $this->validate($request, [
                'status' => 'required|max:50',
                'feedback' => 'required',
            ]);

            $comment = $request->feedback;
        }

        $application->update([
            'status' => $request->status,
            'employer_comment' => $comment,
        ]);

        $application->employer_responded = 1;

        if($application->save())
        {
            // $config = config('services.twilio');

            // // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
            // $AccountSid = $config['acc_id'];

            // $AuthToken = $config['auth_token'];

            // $websiteURL = $config['site_url'];

            // $url = $websiteURL."my/applications/$id";

            // $job_title = $application->advert->job_title;

            // $contact = $application->jobSeeker->user->contact;

            // $JobSeekerName = $application->jobSeeker->user->name;

            // if($application->jobSeeker->user->contact_verified != 0)
            // {
            //     if($application->jobSeeker->user->contact)
            //     {

            //         // Step 3: instantiate a new Twilio Rest Client
            //         $client = new Services_Twilio($AccountSid, $AuthToken);

            //         // Step 4: make an array of people we know, to send them a message. 
            //         // Feel free to change/add your own phone number and name here.
            //         $people = array(
            //             //"+60176613069" => $recipientName,
            //             "+6".$contact => $JobSeekerName,
            //             //"+14158675310" => "Boots",
            //             //"+14158675311" => "Virgil",
            //         );
                    
            //         // Step 5: Loop over all our friends. $number is a phone number above, and 
            //         // $name is the name next to it
            //         foreach ($people as $number => $name) {

            //             $sms = $client->account->messages->sendMessage(

            //                 // Step 6: Change the 'From' number below to be a valid Twilio number 
            //                 // that you've purchased, or the (deprecated) Sandbox number
            //                 "+12602184571", 

            //                 // the number we are sending to - Any phone number
            //                 $number,

            //                 // the sms body
            //                 "Your request job for $job_title has been responded, full details here: $url ."
            //             );
            //             // Display a confirmation message on the screen
            //             //echo "Sent message to $name";
            //         }
            //     }
            // }

            // // testing recipient email => $recipient = "farid@pocketpixel.com";
            // $config = config('services.mailgun');

            // $domain = $config['sender'];

            // $recipient = $application->jobSeeker->user->email;

            // $recipientName = $application->jobSeeker->user->name;

            // $emailView = 'mail.application_notification';

            // $data = [
            //             'websiteURL' => $websiteURL,
            //             'application' => $application
            //         ];

            // $parameter = [
            //                 'domain' => $domain,
            //                 'recipient' => $recipient,
            //                 'recipientName' => $recipientName
            //              ];

            // if($application->jobSeeker->user->verified != 0)
            // {
            //     if($application->jobSeeker->user->email)
            //     {
            //         // use send method from Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            //         Mail::send($emailView, $data, function ($message) use ($parameter) {

            //             // provide sender domain and sender name
            //             $message->from($parameter['domain'], 'WorkWork');

            //             // provide recipient email, recipient name and email subject
            //             $message->to($parameter['recipient'], $parameter['recipientName'])->subject('Application Notification');
            //         });
            //     }
            // }

            // set flash attribute and key. example --> flash('success message', 'flash_message_level')
            flash('Your response has been sent', 'success');

        }else{

            // set flash attribute and key. example --> flash('success message', 'flash_message_level')
            flash('Uh Oh, something went wrong when saving your response. Please try again later.', 'error');

            return redirect()->back();
        }
        return redirect()->back();
    }



    public function appliedApplicantInfo(Request $request, $id, $application_id)
    {
        $advert = Advert::find($id);

        $user = $request->user();

        //check if job advert is own by user
        /*
        if(!$advert->ownedBy($user))
        {
            return $this->unauthorized($request);
        }
        */

        if($advert->employer->user->id != $user->id)
        {
            if($request->ajax())
            {
                return response(['message' => 'No!'], 403);
            }

            flash('not the owner','error');

            return redirect('/');
        }

        $allAdverts = $user->employer->adverts->where('id', '<=', $advert->id);

        if($advert->current_plan === "Free")
        {
            $allowedCount = 3;

            $advertCount = count($allAdverts);

            if($advertCount > $allowedCount)
            {
                flash('Sorry, You are only limited to view the first'.$allowedCount.'request only', 'info');

                return redirect('/adverts');
            }

        }elseif($advert->current_plan === "Trial"){

            $allowedCount = 10;

            $advertCount = count($allAdverts);

            if($advertCount > $allowedCount)
            {
                flash('Sorry, You are only limited to view the first'.$allowedCount.'request only', 'info');

                return redirect('/adverts');
            }

        }

        $application = Application::find($application_id);

        $jobSeeker = JobSeeker::find($application->job_seeker_id);

        $employer = Employer::find($application->employer->id);

        $avatar = $jobSeeker->user->avatar;

        $ratings = $jobSeeker->ownRatings->count();

        $rated = false;

        $haveRating = JobSeekerRating::where('job_seeker_id', $jobSeeker->id)->where('employer_id', $employer->id )->first();


        if($avatar)
        {
            $photo = $jobSeeker->user->avatar;

        }else{

            $photo = "/images/defaults/default.jpg";
        }
        
        if($ratings === 0)
        {
            $average = 0;

        }else{

            $average = $jobSeeker->ownRatings->avg('rating');
        }

        if($haveRating)
        {
            if($haveRating->employer_id === $employer->id)
            {
                $rated = true;
            } 
        }

        return view('profiles.company.applied_applicant_info', compact('id','photo','jobSeeker','rated','average','ratings','application'));
    }



    public function setAsReceived(Request $request)
    {
        $status = $request->status;

        $applicationID = $request->applicationID;

        if($status === 'PENDING')
        {
            $application = Application::findOrFail($applicationID);

            $application->status = "RECEIVED";

            $application->save();
        }
    }

    /**
     * Check if user is authorized
     *
     * @param $request
    
    protected function unauthorized(Request $request)
    {
        if($request->ajax())
        {
            return response(['message' => 'No!'], 403);
        }

        abort(403, 'Unauthorized action.');
    }
     */
}
