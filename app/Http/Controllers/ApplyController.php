<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
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
	    $this->middleware('auth');
	}


	/**
	* return view file
	*/
    public function apply()
	{
		return view('adverts.application_create');
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
