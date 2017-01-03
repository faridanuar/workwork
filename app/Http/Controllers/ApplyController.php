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

		if(!$user->jobSeeker)
		{
			flash("Sorry, you need to create your profile before applying", 'info');

			return redirect('profile/create');
		}else{
			// display only the first retrieved
			$jobSeeker = $user->jobSeeker;
		}

		// display only the first retrieved
		$advert = Advert::locatedAt($id, $job_title)->first();

		$application = Application::where('advert_id', $advert->id)
						->where('job_seeker_id', $jobSeeker->id)
						->where('employer_id', $advert->employer->id)
						->where('responded', 0)->first();

		if($application)
		{
			flash('Please wait until the employer has responded to your previous request', 'info');

			return redirect()->back();
		}

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
			$advert = Advert::locatedAt($id, $job_title)->first();
			$employer = $advert->employer;

		    // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
		    $config = config('services.twilio');
		    $AccountSid = $config['acc_id'];
			$AuthToken = $config['auth_token'];
			$websiteURL = $config['site_url'];

			$url = $websiteURL."$id/job/requests/pending";
			$job_title = $advert->job_title;
			$contact = $employer->user->contact;
			$employerName = $employer->user->name;
			$applicantName = $user->name;

			if($application->employer->user->contact_verified != 0)
		    {
		    	if($application->employer->user->contact)
		    	{
			    	$currentPlan = $advert->current_plan;
			    	$proceed = "false";

			    	switch ($currentPlan)
			        {
			            case "Free":
			                if($advert->sms_count > 0)
			                {
			                	$proceed = "true";
			                }
			                break;
			            case "Trial":
			                if($advert->sms_count > 0)
			                {
			                	$proceed = "true";
			                }    
			                break;
		                case "1_Month_Plan":
		                	$proceed = "true";    
			                break;
		                case "2_Month_Plan":
		                	$proceed = "true";    
			                break;
			            default: 
			        }

			    	if($proceed === "true")
			    	{
					    // Step 3: instantiate a new Twilio Rest Client
					    $client = new Services_Twilio($AccountSid, $AuthToken);

					    // Step 4: make an array of people we know, to send them a message. 
					    // Feel free to change/add your own phone number and name here.
					    $people = array(
					    	//"+60176613069" => $user->name,
					       	"+6".$contact => $employerName,
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
					            "You have a job request for advert: $job_title. Applicant's Name: $applicantName, full details here: $url ."
					        );
					        // Display a confirmation message on the screen
					        //echo "Sent message to $name";

					        if(!$sms)
					        {
					        	echo "Error";
					        }
					    }

					    $subtract = $advert->sms_count - 1;
					    $advert->sms_count = $subtract;
					    $advert->save();
				    }
				}
			}

		    if($application->employer->user->verified != 0)
		    {
		    	if($application->employer->user->email)
		    	{
			    	$emailView = 'mail.job_request';

			    	$data = [
			    				'websiteURL' => $websiteURL, 
			    				'user' => $user, 
			    				'thisJobSeeker' => $thisJobSeeker, 
			    				'application' => $application, 
			    				'advert' => $advert
			    			];

	            	// set the required variables
	            	$config = config('services.mailgun');
		            $domain = $config['sender'];

		            // testing email => $recipient = "farid@pocketpixel.com";
		            $recipient = $application->employer->user->email;
		            $recipientName = $application->employer->user->name;

			    	$parameter = [
			    					'domain' => $domain, 
			    					'recipient' => $recipient,
			    					'recipientName' => $recipientName, 
			    				];

					// use send method from Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
		            Mail::send($emailView, $data, function ($message) use ($parameter) {

			            // provide sender domain and sender name
		                $message->from($parameter['domain'], 'WorkWork');

		                // provide recipient email, name and email subject
		                $message->to($parameter['recipient'], $parameter['recipientName'])->subject('Job Request!');
		            });
		        }
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
