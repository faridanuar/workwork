<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Advert;
use App\Job_Seeker;
use App\Http\Requests;
use App\Http\Requests\ApplicationRequest;

class ApplyController extends Controller
{
	/**
	* Auhthenticate user
	*/
	public function __construct()
	{
	    $this->middleware('jobseeker', ['only' => ['store_apply', 'apply']]);
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
	*/
	public function store_apply(ApplicationRequest $request)
	{

		// create a new user_id and biodata field and store it in jobseekers table
		$request->user()->jobseekers()->create([

			// 'column' => request->'field'
			'biodata' => $request->biodata,
			
		]);

		// set flash attribute and key. example --> flash('success message', 'flash_message_level')
		flash('Your application has been sent. Now you have to wait for confirmation from the employer', 'success');

		return redirect('/adverts');


	}
}
