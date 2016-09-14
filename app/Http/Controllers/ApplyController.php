<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use Services_Twilio;

use App\User;
use App\Advert;
use App\Job_Seeker;
use App\Application;
use App\Http\Requests;
use App\Http\Requests\ApplicationRequest;

class ApplyController extends Controller
{
   /**
	* Auhthenticate user with that has the role of "Job Seeker"
	*/
	public function __construct()
	{
	    $this->middleware('jobSeeker');
	}



	/**
	* return view file
	*/
    public function apply(Request $request, $id, $job_title)
	{
		$user = $request->user();

		// check if user is not logged in
		if(!$user){

			flash("Sorry you need to create a Job Seeker's Profile", 'info');

			return redirect()->back();

		}elseif($user->hasRole('employer') || !$user->hasRole('job_seeker')){

			return redirect('/');

		}elseif(!$user->jobSeeker){

			flash("Sorry you need to create a Job Seeker's Profile", 'info');

			return redirect()->back();

		}else{

			// display only the first retrieved
			$jobSeeker = $user->jobSeeker()->first();

		}

		// display only the first retrieved
		$advert = Advert::locatedAt($id, $job_title)->first();

		return view('adverts.application_create', compact('advert','user', 'jobSeeker'));
	}



	/**
	* storing user's application info
	*
	*@param $id -> get it from url
	*/
	public function storeApply(ApplicationRequest $request, $id, $job_title)
	{
		// fetch User model to find a row of data using user method
		$user = $request->user();

		// fetch Job_Seeker model to find a row of data by referencing users "id" with job_seekers "user_id"
		$thisJobSeeker = $user->jobSeeker;

		// create a new Application model / a new row of data
		$application = new Application;

		$application->introduction = $request->introduction;

		// add a field to "status" column
		$application->status = 'PENDING';

		// use associate method to get model relationship from other Job_Seeker model and store its "id"
		$application->jobSeeker()->associate($thisJobSeeker);

		// use associate method to get model relationship from other Advert model and store its "id"
		$application->advert()->associate($id);

		// save the fields into applications table
		$application->save();


		if($application){
            Mail::send('mail.message', compact('user', 'thisJobSeeker', 'application'), function ($m) use ($user) {
                $m->from('postmaster@sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org', 'WorkWork');

                $m->to('farid@pocketpixel.com', $user->name)->subject('Job Request!');
            });
        }

        $advert = Advert::locatedAt($id, $job_title)->first();

		$employer = $advert->employer;

		$contact = $employer->user->contact;

		if($contact){

			$config = config('services.twilio');

		    // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
		    $AccountSid = $config['acc_id'];
			$AuthToken = $config['auth_token'];

		    // Step 3: instantiate a new Twilio Rest Client
		    $client = new Services_Twilio($AccountSid, $AuthToken);

		    // Step 4: make an array of people we know, to send them a message. 
		    // Feel free to change/add your own phone number and name here.
		    $people = array(
		        "+6$contact" => $user->name,
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
		            "You have a job applicant request from your advert. Applicant: $name, check out the full details here: ."
		        );

		        // Display a confirmation message on the screen
		        echo "Sent message to $name";
		    }

			// set flash attribute and key. example --> flash('success message', 'flash_message_level')
			flash('Your application has been sent. Now you have to wait for confirmation from the employer', 'success');

		    
		}else{

			abort(404, 'Error');
		}

		return redirect('/');
	}

}
