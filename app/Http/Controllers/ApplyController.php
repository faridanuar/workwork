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

		$request->user()->jobseekers()->create([

			'biodata' => $request->biodata,
			
		]);

		return redirect('/adverts');


	}
}
