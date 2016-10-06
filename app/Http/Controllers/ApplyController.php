<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use Services_Twilio;

use App\User;
use App\Advert;
use App\JobSeeker;
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

		if(!$user->jobSeeker){

			flash("Sorry, you need to create your profile before applying", 'info');

			return redirect('profile/create');

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

		// get the appropriate advert with this id and job title
		$advert = Advert::locatedAt($id, $job_title)->firstOrFail();

		// create a new Application model / a new row of data
		$application = new Application;

		$application->introduction = $request->introduction;

		// add a field to "status" column
		$application->status = 'PENDING';

		// use associate method to get model relationship from other Job_Seeker model and store its "id"
		$application->jobSeeker()->associate($thisJobSeeker);

		// use associate method to get model relationship from other Advert model and store its "id"
		$application->advert()->associate($id);

		// use associate method to get model relationship from other Employer model and store its "id"
		$application->employer()->associate($advert->employer_id);

		if($application->save())
		{
			// use send method from Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.message', compact('user', 'thisJobSeeker', 'application', 'advert'), function ($m) use ($user) {

            	// set the required variables
            	$config = config('services.mailgun');
	            $domain = $config['sender'];
	            $recipient = 'farid@pocketpixel.com';
	            $recipientName = $user->name;

	            // provide sender domain and sender name
                $m->from($domain, 'WorkWork');

                // provide recipient email, name and email subject
                $m->to($recipient, $recipientName)->subject('Job Request!');
            });

	        $advert = Advert::locatedAt($id, $job_title)->first();
			$employer = $advert->employer;
			$contact = $employer->user->contact;
			$config = config('services.twilio');

		    // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
		    $AccountSid = $config['acc_id'];
			$AuthToken = $config['auth_token'];
			$url = "http://workwork.my/$id/job/requests/pending";
			$job_title = $advert->job_title;

		    // Step 3: instantiate a new Twilio Rest Client
		    $client = new Services_Twilio($AccountSid, $AuthToken);

		    // Step 4: make an array of people we know, to send them a message. 
		    // Feel free to change/add your own phone number and name here.
		    $people = array(
		    	"+60176613069" => $user->name,
		       // "+6$contact" => $user->name,
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
		            "You have a job applicant request from your advert: $job_title. Applicant Name: $name, check out the full details here: $url ."
		        );
		        // Display a confirmation message on the screen
		        //echo "Sent message to $name";
		    }
			// set flash attribute and key. example --> flash('success message', 'flash_message_level')
			flash('Your application has been sent. Now you have to wait for confirmation from the employer', 'success');
		}else{
			// set flash attribute and key. example --> flash('success message', 'flash_message_level')
			flash('Uh Oh, something went wrong when saving your application. Please try again later.', 'error');
			return redirect()->back();
		}

		return redirect()->route('show', [$id,$job_title]);
	}

}
