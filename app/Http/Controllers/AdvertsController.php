<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Advert;
use App\Skill;
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

		if($user->ftu_level < 4)
		{
			$done = 3;
	        $notDone = 3;
    	}else{
    		$done = 2;
	        $notDone = 2;
    	}

		// display "show" page
		return view('adverts.show', compact('advert','authorize','asEmployer','user','done','notDone'));
	}



	/**
	 * Create a new advert
	 */
	public function create(Request $request)
	{
		$user = $request->user();

		if(!$user->employer)
		{
			flash('You need a profile to create an advert', 'info');

			return redirect('/company/create');
		}

		if($user->ftu_level < 4)
		{
			// ftu level
			$done = 1;
        	$notDone = -2;
    	}else{
    		// advert level
    		$done = 0;
	        $notDone = -2;
    	}
		

		return view('adverts.create', compact('user','done','notDone'));
	}



	public function store(AdvertRequest $request)
	{
		$saveLater = $request->saveLater;

		if($saveLater != true){
			$this->validate($request, [
		        'job_title' => 'required|max:50',
		        'salary' => 'required|integer',
	            'description' => 'required',           
	            'location' => 'required',
	            'skills' => 'required',
	            'category' => 'required',
	            'rate' => 'required',
	    	]);
		}

		$user = $request->user();

		if($user->avatar != null || $user->avatar != "")
		{
			$avatar = $user->avatar;
		}else{
			$avatar = "/images/defaults/default.jpg";
		}

		if($request->oku_friendly != null){
			$oku_friendly = "Yes";
		}else{
			$oku_friendly = "No";
		}

		$employer = $user->employer;

		// what do we need to do? if the request validates, the body below of this method will be hit
		// validate the form - DONE		
		// persist the advert - DONE
		$advert = $employer->adverts()->create([
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
		        'category'  => $request->category,
		        'rate'  => $request->rate,
		        'oku_friendly'  => $oku_friendly,
		        'avatar'  => $avatar,
			]);

		$arrayOfSkills = explode(",",$request->skills);

		foreach($arrayOfSkills as $skill){
			$newSkill = new Skill;
			$newSkill->skill = $skill;
			$newSkill->save();
			$advert->skills()->attach($newSkill);
		}

		switch ($saveLater)
		{
			case "true":
				$advert->ready_to_publish = 0;
				$advert->save();
				flash('Your advert has been successfully saved but not yet published', 'info');
				return redirect('/adverts');
				break;
			default:
				$advert->ready_to_publish = 1;
				$advert->save();
				if($user->ftu_level < 4)
				{
					$user->ftu_level = 2;
					$user->save();
				}elseif($advert->advert_level < 3){
					$advert->advert_level = 1;
					$advert->save();
				}
		}

		return redirect()->route('plan', [$advert->id]);
	}



	/**
	 * Store a newly created resource in storage
	 *
	 * @param AdvertRequest $request
	 */
	public function publish(Request $request, Search $search)
	{
		$advert = Advert::find($request->id);

		$ready = $advert->ready_to_publish;

        switch ($ready)
        {
        	case 0:
	        	flash('You need to update your advert, then publish', 'info');
	        	return redirect()->back();
	        	break;
        	default:
        }

        $todaysDate = Carbon::now();

        $endDate = $advert->plan_ends_at;

        $daysLeft =  $todaysDate->diffInDays($endDate, false);

        if($endDate === null){

        	flash('You need to purchase a plan to published your job advert', 'info');

            return redirect()->route('plan', [$advert->id]);

        }elseif($daysLeft < 0){

            flash('your package has been expired, please purchase a new plan', 'info');

            return redirect()->route('plan', [$advert->id]);
        }

		$advert->published = 1;
		$advert->advert_level = 3;
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
				$user = $request->user();
				$user->ftu_level = 4;
				$user->save();

				// set flash attribute and key. example --> flash('success message', 'flash_message_level')
				flash('Your advert has been successfully published.', 'success');

				// redirect to a landing page, so that people can share to the world DONE, kinda
				// next, flash messaging
				return redirect()->back();
				
			}else{

				flash('There was something wrong when publishing your advert. Please try again.', 'error');

				return redirect()->back();
			}

		}else{

			flash('There was something wrong when saving your advert. Please try again.', 'error');

			return redirect()->back();
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

				return redirect()->back();
				
			}else{

				flash('There was something wrong when unpublishing your advert. Please try again.', 'error');

				return redirect()->back();
			}

		}else{

			flash('There was something wrong when changing your advert state. Please try again.', 'error');

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
	public function update(AdvertRequest $request, Search $search, $id, $job_title)
	{
		$advert = Advert::find($id);

		$saveLater = $request->saveLater;

		if($saveLater != true){
			$this->validate($request, [
		        'job_title' => 'required|max:50',
		        'salary' => 'required|integer',
	            'description' => 'required',           
	            'location' => 'required',
	            'country' => 'required',
	            'category' => 'required',
	            'rate' => 'required',
	            'oku_friendly' => 'required',
	    	]);
		}

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
		    'category'  => $request->category,
		    'rate'  => $request->rate,
		    'oku_friendly'  => $request->oku_friendly,
		    'schedule' => $request->schedule,
		]);
		$advert->save();

		$arrayOfSkills = explode(",",$request->skills);

		foreach($arrayOfSkills as $skill){
			$newSkill = new Skill;
			$newSkill->skill = $skill;
			$newSkill->save();
			$advert->skills()->attach($newSkill);
		}

		switch ($saveLater)
		{
			case "true":
				flash('Changes made from your advert has been successfully saved but not yet published', 'info');
				return redirect('/my/adverts');
				break;
			default:
				$advert->ready_to_publish = 1;
				$advert->save();

				$todaysDate = Carbon::now();

		        $endDate = $advert->plan_ends_at;

		        $daysLeft =  $todaysDate->diffInDays($endDate, false);

		        if($endDate === null){

		        	flash('You need to purchase a plan to published your job advert', 'info');

		            return redirect()->route('plan', [$advert->id]);

		        }elseif($daysLeft < 0){

		            flash('your package has been expired, please purchase a new plan', 'info');

		            return redirect()->route('plan', [$advert->id]);
		        }
		}

		if($advert->published != 0){

			$config = config('services.algolia');

			$index = $config['index'];
		
			$indexFromAlgolia = $search->index($index);

			$object = $indexFromAlgolia->partialUpdateObject([
		    	'id' => $advert->id,
		        'job_title' => $advert->job_title,
		        'salary' => (float)$advert->salary,
		        'description' => $advert->description,
		        'business_name' => $advert->business_name,
		        'location' => $advert->location,
		        'street' => $advert->street,
		        'city' => $advert->city,
		        'zip' => $advert->zip,
		        'state' => $advert->state,
		        'country' => $advert->country,
		        'created_at' => $advert->created_at->toDateTimeString(),
		        'updated_at' => $advert->updated_at->toDateTimeString(),
		        'employer_id' => $advert->employer_id,
		        'skill' => $advert->skill,
		        'category' => $advert->category,
		        'rate' => $advert->rate,
		        'oku_friendly' => $advert->oku_friendly,
		        'published' => $advert->open,
		        'schedule' => $advert->schedule,
		        'objectID' => $advert->id,
			]);

			$advert->published = 1;
			$advert->save();

			if($object)
			{
				flash('Your advert has been successfully published.', 'success');

				return redirect()->route('show', [$id,$advert->job_title]);

			}else{

				flash('There was something wrong when publishing your advert. Please try again.', 'error');

				return redirect()->back();
			}

		}else{
			return redirect()->route('plan', [$id]);
		}
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
