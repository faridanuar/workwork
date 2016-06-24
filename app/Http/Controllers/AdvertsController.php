<?php

namespace App\Http\Controllers;


use App\Advert;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\AdvertRequest;

class AdvertsController extends Controller
{
	/**
	* Auhthenticate user
	*/
	public function __construct()
	{
	    $this->middleware('employer', ['except' => ['index', 'show']]);
	}


	/**
	 * Index - list of adverts
	 */
	public function index()
	{
		$adverts = Advert::all();

		return view('adverts.index', compact('adverts'));
	}

	/**
	 * Create a new advert
	 */
	public function create()
	{
		return view('adverts.create');
	}

	/**
	 * Store a newly created resource in storage
	 *
	 * @param AdvertRequest $request
	 */
	public function store(AdvertRequest $request)
	{

		$user = $request->user();

		$employer = $user->employer()->first();

	// what do we need to do? if the request validates, the body below of this method will be hit
		// validate the form - DONE		
		// persist the advert - DONE
		//Advert::create($request->all());
		$employer->advert()->create($request->all());

		// redirect to a landing page, so that people can share to the world DONE, kinda
		// next, flash messaging
		return redirect()->back();
	}


	public function edit(Request $request, $id, $job_title)
	{
		$user = $request->user();

		// display only the first retrieved
		$job = Advert::locatedAt($id, $job_title)->first();


		if(! $job->ownedBy($user))
		{
			return $this->unauthorized($request);
		}


		// display "edit" page
		return view('adverts.edit', compact('job'));

	}

	protected function unauthorized(Request $request)
	{
		if($request->ajax())
			{
				return response(['message' => 'No!'], 403);
			}


			flash('Sorry, you are not the owner of that page');

			return redirect('/adverts');
	}

	public function update(AdvertRequest $request, $id, $job_title)
	{

		$advert = Advert::find($id);

		$advert->update([

				'job_title' => $request->job_title,
				'salary' => $request->salary,
				'description' => $request->description,
				'business_name' => $request->business_name,
				'location' => $request->location,
				'street' => $request->street,
				'city' => $request->city,
				'zip' => $request->zip,
				'state' => $request->state,
				'country' => $request->country,


		]);

		$advert->save();


		return redirect('/adverts');
	}


	/**
	*show existing data in storage
	*
	*calling locatedAt function from Advert MODEL
	*
	*@param $id, $job_title
	*/
	public function show($id, $job_title)
	{

		// fetch only the first retrieved
		$job = Advert::locatedAt($id, $job_title)->first();
		
		// display "show" page
		return view('adverts.show', compact('job'));


	}
}
