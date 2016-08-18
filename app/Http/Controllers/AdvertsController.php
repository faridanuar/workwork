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
		$config = config('services.algolia');

		$id = $config['app_id'];
		$api = $config['api_key'];
		
		return view('adverts.index', compact('id', 'api'));
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
		$advert = Advert::locatedAt($id, $job_title)->first();

		$advertEmployer = $advert->employer_id;

		$user = $request->user();


		$authorize = "";

		$asEmployer = false;


		if($user)
		{
			$thisEmployer = $user->employer;

			if($thisEmployer){

				$asEmployer = true;

				if ($advertEmployer === $thisEmployer->id)
				{
					$authorize = true;

				}else{

					$authorize = false;
				}
			}
		}

		// display "show" page
		return view('adverts.show', compact('advert', 'authorize', 'asEmployer'));
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
		'category' => $request->category,
		'rate' => $request->rate,
		'oku_friendly' => $request->oku_friendly
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

		$employer = $user->employer;

		// what do we need to do? if the request validates, the body below of this method will be hit
		// validate the form - DONE		
		// persist the advert - DONE
		//Advert::create($request->all());
		$saveToDatabase = $employer->advert()->create(
			[
		    	'id' => $request->id,
		        'job_title' => $request->job_title,
		        'salary'  => (float)$request->salary,
		        'description'  => $request->description,
		        'business_name'  => $employer->business_name,
		        'location'  => $request->location,
		        'street'  => $request->street,
		        'city'  => $request->city,
		        'zip'  => $request->zip,
		        'state'  => $request->state,
		        'country'  => $request->country,
		        'employer_id'  => $request->employer_id,
		        'skill'  => $request->skill,
		        'category'  => $request->category,
		        'rate'  => $request->rate,
		        'oku_friendly'  => $request->oku_friendly,
			]
		);

		if($saveToDatabase)
		{
			$indexFromAlgolia = $search->index('adverts');

			$object = $indexFromAlgolia->addObject(
		
			    [
			    	'id' => $saveToDatabase->id,
			        'job_title' => $saveToDatabase->job_title,
			        'salary'  => (float)$saveToDatabase->salary,
			        'description'  => $saveToDatabase->description,
			        'business_name'  => $saveToDatabase->business_name,
			        'location'  => $saveToDatabase->location,
			        'street'  => $saveToDatabase->street,
			        'city'  => $saveToDatabase->city,
			        'zip'  => $saveToDatabase->zip,
			        'state'  => $saveToDatabase->state,
			        'country'  => $saveToDatabase->country,
			        'created_at'  => $saveToDatabase->created_at->toDateTimeString(),
			        'updated_at'  => $saveToDatabase->updated_at->toDateTimeString(),
			        'employer_id'  => $saveToDatabase->employer_id,
			        'skill'  => $saveToDatabase->skill,
			        'category'  => $saveToDatabase->category,
			        'rate'  => $saveToDatabase->rate,
			        'oku_friendly'  => $saveToDatabase->oku_friendly,
			    ],
			    $saveToDatabase->id
			);

			if($object)
			{
				$id = $saveToDatabase->id;

				$job_title = $saveToDatabase->job_title;

				// set flash attribute and key. example --> flash('success message', 'flash_message_level')
				flash('Your advert has been successfully created.', 'success');

				// redirect to a landing page, so that people can share to the world DONE, kinda
				// next, flash messaging
				return redirect()->route('show', [$id,$job_title]);


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
		$advert = Advert::locatedAt($id, $job_title)->first();

		//check if job advert is own by user
		if(! $advert->ownedBy($user))
		{
			return $this->unauthorized($request);
		}

		// display "edit" page
		return view('adverts.edit', compact('advert'));
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

			return redirect('/');
	}



	/**
	 * Update existing advert
	 *
	 * @param $request, $id, $job_title
	 */
	public function update(Search $search, AdvertRequest $request, $id, $job_title)
	{
		$advert = Advert::find($id);

		$business = $advert->employer->business_name;

		$advert->update([

				'job_title' => $request->job_title,
				'salary' => (float)$request->salary,
				'description' => $request->description,
				'business_name' => $business,
				'location' => $request->location,
				'street' => $request->street,
				'city' => $request->city,
				'zip' => $request->zip,
				'state' => $request->state,
				'country' => $request->country,
				'skill'  => $request->skill,
			    'category'  => $request->category,
			    'rate'  => $request->rate,
			    'oku_friendly'  => $request->oku_friendly,


		]);

		 $advert->save();
			

		$indexFromAlgolia = $search->index('adverts');

		$object = $indexFromAlgolia->saveObject(
		    [
		    	'id' => $advert->id,
		        'job_title' => $advert->job_title,
		        'salary'  => (float)$advert->salary,
		        'description'  => $advert->description,
		        'business_name'  => $advert->business_name,
		        'location'  => $advert->location,
		        'street'  => $advert->street,
		        'city'  => $advert->city,
		        'zip'  => $advert->zip,
		        'state'  => $advert->state,
		        'country'  => $advert->country,
		        'created_at'  => $advert->created_at->toDateTimeString(),
		        'updated_at'  => $advert->updated_at->toDateTimeString(),
		        'employer_id'  => $advert->employer_id,
		        'skill'  => $advert->skill,
		        'category'  => $advert->category,
		        'rate'  => $advert->rate,
		        'oku_friendly'  => $advert->oku_friendly,
		        'objectID'  => $advert->id,
		    ]
		);

		if($object)
		{
			flash('Your advert has been successfully updated.', 'success');

			return redirect()->route('show', [$id,$job_title]);

		}else{

			echo "Error: updating to index was unsuccessful.";
		}
	}



	public function myAdverts($id, $business_name)
	{
		$adverts = Adverts::find('employer_id', $id)->get();

		return view('profiles.adverts', compact('adverts'));
	}
}
