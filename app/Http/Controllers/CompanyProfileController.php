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
        $company = Employer::findEmployer($id, $business_name)->first();

        $user = $request->user();

        if($company->business_logo != "" OR $company->business_logo != null)
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

        //check if photo path exist
        if($logo != "" || $logo != null){

            $fileExist = true;
            $photo = $logo;

        }else{

            $fileExist = false;
            $photo = "/images/defaults/default.jpg";
        }

        return view('profiles.company.logo', compact('photo','employer','fileExist'));
    }



    protected function uploadLogo(Request $request)
    {
        // store user's info in variable
        $employer = $request->user()->employer()->first();

        $this->validate($request, [

            'photo' => 'required|mimes:jpg,jpeg,png,bmp' // validate image
        ]);

    	$file = $request->file('photo');

    	$name = time(). '-' .$file->getClientOriginalName();

    	$path = "images/profile_images/logo/logo";

        Image::make($file)->fit(200, 200)->save($path."/".$name);

    	$employer->update([ 'business_logo' => "/".$path."/".$name ]);

        $employer->save();
    }



    public function remove(Request $request, $logo_id)
    {
        $employer = Employer::findOrFail($logo_id);

        $logo = $employer->business_logo;

        $user = $request->user();

        //check if job advert is own by user
        if(!$employer->logoBy($user))
        {
            return $this->unauthorized($request);
        }

        if($logo != "" || $logo != null){

            $exist = true;

        }else{

            $exist = false;
        }

        if($exist === true){

                $employer->update([ 'business_logo' => null ]);

                $employer->save();

                flash('Your photo has been successfully removed', 'success');

                return redirect()->back();

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

        $rating = new JobSeekerRating;

        $rating->ratings = $request->star;

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

        $myAdverts = Advert::where('employer_id', $employerID)->orderBy('published', 'desc')->orderBy('created_at', 'desc')
                ->get();

        return view('profiles.company.company_adverts', compact('myAdverts'));
    }



    public function allList($id)
    {
        $advert = Advert::find($id);
        $currentPlan = $advert->current_plan;

        if($currentPlan === 'Trial')
        {
            $limitedRequest = Application::where('advert_id', $id)->take(3);
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

        if($currentPlan === 'Trial')
        {
            $limitedRequest = Application::where('advert_id', $id)->take(3);
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

        if($status != "REJECTED"){

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

        $application->responded = 1;

        if($application->save())
        {
            $config = config('services.twilio');

            // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
            $AccountSid = $config['acc_id'];
            $AuthToken = $config['auth_token'];
            $websiteURL = $config['site_url'];
            $url = $websiteURL."my/applications/$id";
            $job_title = $application->advert->job_title;
            $contact = $application->jobSeeker->user->contact;
            $JobSeekerName = $application->jobSeeker->user->name;

            // Step 3: instantiate a new Twilio Rest Client
            $client = new Services_Twilio($AccountSid, $AuthToken);

            // Step 4: make an array of people we know, to send them a message. 
            // Feel free to change/add your own phone number and name here.
            $people = array(
                //"+60176613069" => $recipientName,
                "+6".$contact => $JobSeekerName,
                //"+14158675310" => "Boots",
                //"+14158675311" => "Virgil",
            );
            
            // Step 5: Loop over all our friends. $number is a phone number above, and 
            // $name is the name next to it
            foreach ($people as $number => $name) {

                $sms = $client->account->messages->sendMessage(

                    // Step 6: Change the 'From' number below to be a valid Twilio number 
                    // that you've purchased, or the (deprecated) Sandbox number
                    "+12602184571", 

                    // the number we are sending to - Any phone number
                    $number,

                    // the sms body
                    "Your request job for $job_title has been responded, full details here: $url ."
                );
                // Display a confirmation message on the screen
                //echo "Sent message to $name";
            }

            // use send method from Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.applicationNotification', compact('websiteURL', 'application'), function ($m) use ($application) {

                // set the required variables
                $config = config('services.mailgun');

                $domain = $config['sender'];

                $recipient = $application->jobSeeker->user->email;
                //$recipient = "farid@pocketpixel.com";
                
                $recipientName = $application->jobSeeker->user->name;
                
                // provide sender domain and sender name
                $m->from($domain, 'WorkWork');

                // provide recpient email, recepient name and email subject
                $m->to($recipient, $recipientName)->subject('Application Notification');
            });

            // set flash attribute and key. example --> flash('success message', 'flash_message_level')
            flash('Your response has been sent', 'success');
        }else{
            // set flash attribute and key. example --> flash('success message', 'flash_message_level')
            flash('Uh Oh, something went wrong when saving your response. Please try again later.', 'error');
            return redirect()->back();
        }
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

        $profileInfo = JobSeeker::find($role_id);

        $avatar = $profileInfo->user->avatar;

        if($avatar != "" || $avatar != null){

            $photo = $profileInfo->user->avatar;

        }else{

            $photo = "/images/defaults/default.jpg";
        }

        $request = Application::where('advert_id', $id)->where('job_seeker_id', $role_id)->first();

        $ratings = $profileInfo->ownRatings->count();

        $rated = false;

        if($ratings === 0)
        {
            $average = 0;

        }else{

            $average = $profileInfo->ownRatings->avg('rating');
        }


        if($user)
        {
            $employer = $user->employer;

            $haveRating = JobSeekerRating::where('job_seeker_id', $role_id)->where('employer_id', $employer->id)->first();

            if($haveRating === null){

                $rated = false;

            }else{

                if($haveRating->employer_id === $employer->id)
                {
                    $rated = true;
                } 
            }    
        }

        return view('profiles.company.request_applied', compact('id','photo','profileInfo','rated','average','ratings','request'));
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
