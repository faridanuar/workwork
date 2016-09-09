<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Advert;
use App\Employer;

use App\Contracts\Search;

use App\Http\Requests;
use App\Http\Requests\AdvertRequest;

use Carbon\Carbon;



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
		$api = $config['search_key'];
		$index = $config['index'];
		$index_asc = $config['index_asc'];
		$index_desc = $config['index_desc']; 
		
		return view('adverts.index', compact('id', 'api', 'index', 'index_asc', 'index_desc'));
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
		$advert = Advert::locatedAt($id, $job_title)->firstOrFail();

		$status = $advert->open;

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



	public function store(AdvertRequest $request)
	{
		$user = $request->user();

		$employer = $user->employer;

		if($user->avatar != null || $user->avatar != "")
		{
			$avatar = $user->avatar;

		}else{

			$avatar = "/images/defaults/default.jpg";
		}

		// what do we need to do? if the request validates, the body below of this method will be hit
		// validate the form - DONE		
		// persist the advert - DONE
		//Advert::create($request->all());
		$saveToDatabase = $employer->advert()->create(
			[
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
		        'avatar'  => $avatar,
			]
		);

		$saveForLater = $request->later;

		switch ($saveForLater)
		{
			case "true":
				return redirect('dashboard');
				break;
			default:
				$saveToDatabase->published = 1;
				$saveToDatabase->save();
		}

		return redirect()->route('plan', [$saveToDatabase->id]);
		//return redirect()->route('show', [$saveToDatabase->id,$saveToDatabase ->job_title]);
	}



	/**
	 * Store a newly created resource in storage
	 *
	 * @param AdvertRequest $request
	 */
	public function publish(Request $request, Search $search)
	{
		$advert = Advert::find($request->id);
		$todaysDate = Carbon::now();
        $endDate = $advert->ends_at;
        $daysLeft =  $todaysDate->diffInDays($endDate, false);

        if($endDate === null){

        	flash('You need to purchase a plan to published your job ad', 'info');

            return redirect()->route('plan', [$advert->id]);

        }elseif($daysLeft < 0){

            flash('your package has been expired, please purchase a new plan', 'info');

            return redirect()->route('plan', [$advert->id]);
        }

		$advert->published = 1;
		$advert->save();

		if($advert)
		{
			$config = config('services.algolia');

			$index = $config['index'];

			$indexFromAlgolia = $search->index($index);

			$object = $indexFromAlgolia->addObject(
		
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
			        'open' => $advert->open,
			        'avatar'  => $advert->avatar,
			        'schedule_id'  => $advert->schedule_id,
			    ],
			    $advert->id
			);

			if($object)
			{
				// set flash attribute and key. example --> flash('success message', 'flash_message_level')
				flash('Your advert has been successfully published.', 'success');

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



	public function unpublish(Request $request, Search $search)
	{
		$advert = Advert::find($request->id);
		$advert->published = 0;
		$advert->save();

		if($advert)
		{
			$config = config('services.algolia');

			$index = $config['index'];

			$indexFromAlgolia = $search->index($index);

			$object = $indexFromAlgolia->deleteObject($advert->id);

			if($object)
			{
				// set flash attribute and key. example --> flash('success message', 'flash_message_level')
				flash('Your advert has been unpublished.', 'info');

				// redirect to a landing page, so that people can share to the world DONE, kinda
				// next, flash messaging
				return redirect()->back();
				
			}else{

				flash('Error: publishing to index was unsuccessful, please try again', 'error');

				return redirect()->back();
			}

		}else{

			flash('Error: saving was unsuccessful, please try again', 'error');

			return redirect()->back();
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
	 * Update existing advert
	 *
	 * @param $request, $id, $job_title
	 */
	public function update(Search $search, AdvertRequest $request, $id, $job_title)
	{
		$advert = Advert::find($id);

		$business = $advert->employer->business_name;

		$config = config('services.algolia');

		$index = $config['index'];

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
		    'schedule' => $request->schedule,
		]);
		$advert->save();

		if($advert->published != 0){
		
			$indexFromAlgolia = $search->index($index);

			$object = $indexFromAlgolia->partialUpdateObject([

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
		        'published' => $advert->open,
		        'schedule'  => $advert->schedule,
		        'objectID'  => $advert->id,
			]);

			if($object)
			{
				flash('Your advert has been successfully updated to index.', 'success');

				return redirect()->route('show', [$id,$advert->job_title]);

			}else{

				flash('Error: updating to index was unsuccessful.', 'error');

				return redirect()->back();
			}

		}else{

			flash('Your advert has been successfully updated.', 'success');

			return redirect()->route('show', [$id,$advert->job_title]);
		}
	}



	public function myAdverts($id, $business_name)
	{
		$adverts = Adverts::find('employer_id', $id)->get();

		return view('profiles.adverts', compact('adverts'));
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
}
