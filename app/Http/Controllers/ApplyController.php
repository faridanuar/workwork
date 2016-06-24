<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Advert;
use App\Job_Seeker;
use App\Application;
use App\Http\Requests;
use App\Http\Requests\ApplicationRequest;

class ApplyController extends Controller
{
	/**
	* Auhthenticate user
	*/
	public function __construct()
	{
	    $this->middleware('jobSeeker', ['only' => ['store_apply', 'apply']]);
	}


	/**
	* return view file
	*/
    public function apply($id, $job_title)
	{

		// display only the first retrieved
		$job = Advert::locatedAt($id, $job_title)->first();

		return view('adverts.application_create', compact('job'));

	}

	/**
	* storing user's application info
	*
	*@param $id -> get it from url
	*/
	public function storeApply(ApplicationRequest $request, $id)
	{
		// fetch User model to find a row of data using user method
		$user = $request->user();

		// fetch Job_Seeker model to find a row of data by referencing users "id" with job_seekers "user_id"
		$jobSeeker = $user->jobSeeker()->first();



		// check if Job_Seeker has already have a row of data with this user
		if(count($jobSeeker) == 1){

			// create a new Application model / a new row of data
			$application = new Application;

			// add a field to "status" column
			$application->status = 'PENDING';

			// use associate method to get model relationship from other Job_Seeker model and store its "id"
			$application->jobSeeker()->associate($jobSeeker);

			// use associate method to get model relationship from other Advert model and store its "id"
			$application->advert()->associate($id);

			// save the fields into applications table
			$application->save();


		}else{


			// create a new user_id and fields and store it in jobseekers table
			$jobSeeker = $user->jobSeeker()->create([

				// 'column' => request->'field'
				'biodata' => $request->biodata,
				'name' => $request->name,
				'age' => $request->age,
				'contact' => $request->contact,
				'location' => $request->location,
				'street' => $request->street,
				'city' => $request->city,
				'zip' => $request->zip,
				'state' => $request->state,
				'country' => $request->country,
				
			]);

			// create a new Application model / a new row of data
			$application = new Application;

			// add a field to "status" column
			$application->status = 'PENDING';

			// use associate method to get model relationship from other Job_Seeker model and store its "id"
			$application->jobSeeker()->associate($jobSeeker);

			// use associate method to get model relationship from other Advert model and store its "id"
			$application->advert()->associate($id);

			// save the fields into applications table
			$application->save();

		}

		// set flash attribute and key. example --> flash('success message', 'flash_message_level')
		flash('Your application has been sent. Now you have to wait for confirmation from the employer', 'success');

		return redirect('/adverts');


	}
}
