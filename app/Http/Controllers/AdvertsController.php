<?php

namespace App\Http\Controllers;


use App\Advert;
use App\Http\Requests;
use App\Contracts\Search;
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
		$adverts = Advert::orderBy('id', 'desc')->paginate(5);

		return view('adverts.index', compact('adverts'));
	}



	/**
	 * Create a new advert
	 */
	public function create()
	{
		return view('adverts.create');
	}



	public function preview(Request $request)
	{
		$details = [
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
		'skill' => $request->skill,
		'category' => $request->category
		];

		return view('adverts.preview', compact('details'));
	}



	/**
	 * Store a newly created resource in storage
	 *
	 * @param AdvertRequest $request
	 */
	public function store(Search $search, AdvertRequest $request)
	{

		$user = $request->user();

		$employer = $user->employer()->first();

		// what do we need to do? if the request validates, the body below of this method will be hit
		// validate the form - DONE		
		// persist the advert - DONE
		//Advert::create($request->all());
		$saveToDatabase = $employer->advert()->create($request->all());

		if($saveToDatabase)
		{
			$indexFromAlgolia = $search->index('adverts');

			$object = $indexFromAlgolia->addObject(
		
			    [
			    	'id' => $saveToDatabase->id,
			        'job_title' => $saveToDatabase->job_title,
			        'salary'  => $saveToDatabase->salary,
			        'description'  => $saveToDatabase->description,
			        'business_name'  => $saveToDatabase->business_name,
			        'business_logo'  => $saveToDatabase->business_logo,
			        'location'  => $saveToDatabase->location,
			        'street'  => $saveToDatabase->street,
			        'city'  => $saveToDatabase->city,
			        'zip'  => $saveToDatabase->zip,
			        'state'  => $saveToDatabase->state,
			        'country'  => $saveToDatabase->country,
			        'created_at'  => $saveToDatabase->created_at,
			        'updated_at'  => $saveToDatabase->updated_at,
			        'employer_id'  => $saveToDatabase->employer_id,
			        'skill'  => $saveToDatabase->skill,
			        'category'  => $saveToDatabase->category,
			    ],
			    $saveToDatabase->id
			);

			if($object)
			{

				// set flash attribute and key. example --> flash('success message', 'flash_message_level')
				flash('Your advert has been successfully created. Go to advert index to find your advert', 'success');

				// redirect to a landing page, so that people can share to the world DONE, kinda
				// next, flash messaging
				return redirect()->back();


			}else{

				echo "Error: Adding object to index was unsuccessful";
			}

		}else{

			echo "Error: unable to save record to database. ";
		}


		
	}



	/**
	 * Edit created resource in storage
	 *
	 * @param $request, $id, $job_title
	 */
	public function edit(Request $request, $id, $job_title)
	{

		//get log in user data
		$user = $request->user();

		// display only the first retrieved
		$job = Advert::locatedAt($id, $job_title)->first();


		//check if job advert is own by user
		if(! $job->ownedBy($user))
		{
			return $this->unauthorized($request);
		}


		// display "edit" page
		return view('adverts.edit', compact('job'));

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


			flash('Sorry, you are not the owner of that page');

			return redirect('/adverts');
	}



	/**
	 * Update existing advert
	 *
	 * @param $request, $id, $job_title
	 */
	public function update(Search $search, AdvertRequest $request, $id)
	{

		$advert = Advert::find($id);

		$advert->update([

				'job_title' => $request->job_title,
				'salary' => $request->salary,
				'description' => $request->description,
				'business_name' => $request->business_name,
				'business_logo'  => $request->business_logo,
				'location' => $request->location,
				'street' => $request->street,
				'city' => $request->city,
				'zip' => $request->zip,
				'state' => $request->state,
				'country' => $request->country,
				'skill'  => $request->skill,
			    'category'  => $request->category,


		]);

		$updateToDatabase = $advert->save();

		if($updateToDatabase)
		{
			$advert->save();

			$indexFromAlgolia = $search->index('adverts');

			$object = $indexFromAlgolia->saveObject(
			    [
			        'job_title' => $updateToDatabase->job_title,
			        'salary'  => $updateToDatabase->salary,
			        'description'  => $updateToDatabase->description,
			        'business_name'  => $updateToDatabase->business_name,
			        'business_logo'  => $updateToDatabase->business_logo,
			        'location'  => $updateToDatabase->location,
			        'street'  => $updateToDatabase->street,
			        'city'  => $updateToDatabase->city,
			        'zip'  => $updateToDatabase->zip,
			        'state'  => $updateToDatabase->state,
			        'country'  => $updateToDatabase->country,
			        'created_at'  => $updateToDatabase->created_at,
			        'updated_at'  => $updateToDatabase->updated_at,
			        'employer_id'  => $updateToDatabase->employer_id,
			        'skill'  => $updateToDatabase->skill,
			        'category'  => $updateToDatabase->category,
			        'objectID'  => $updateToDatabase->id,
			    ]
			);

			if($object)
			{

				return redirect('/adverts');

			}else{

				echo "Error: updating to index was unsuccessful.";
			}

		}else{

			echo "Error: Updating to database was unsuccessful.";
		}

		
	}



	/**
	*show existing data in storage
	*
	*calling locatedAt function from Advert MODEL
	*
	*@param $request, $id, $job_title
	*/
	public function show(Request $request, $id, $job_title)
	{

		// fetch only the first retrieved
		$job = Advert::locatedAt($id, $job_title)->first();

		$advertEmployer = $job->employer_id;

		$user = $request->user();

		$authorize = "";


		if($user)
		{
			$thisEmployer = $user->employer;

			if($thisEmployer){

				if ($advertEmployer === $thisEmployer->id)
				{
					$authorize = true;

				}else{

					$authorize = false;
				}
			}
		}

		// display "show" page
		return view('adverts.show', compact('job', 'authorize'));


	}
}
