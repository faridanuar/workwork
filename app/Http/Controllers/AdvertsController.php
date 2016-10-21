<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Advert;
use App\Skill;
use App\Employer;
use App\SpecificSchedule;
use App\DailySchedule;

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
	public function index(Request $request)
	{
		if($request->user())
		{
			if($request->user()->jobSeeker){
				$plucked = $request->user()->jobSeeker->categories->pluck('name');
				$categories = $plucked->all();
				$categories = implode(",", $categories);
				//dd($categories);
			}else{
				$categories = false;
			}
		}else{
			$categories = false;
		}
		return view('adverts.index', compact('categories'));
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
		$authorize = "";
		$asEmployer = false;
		$user = $request->user();

		if($user)
		{
			if($user->ftu_level < 4)
			{
				$done = 3;
		        $notDone = 3;
	    	}else{
	    		$done = 2;
		        $notDone = 2;
	    	}

			$thisEmployer = $user->employer;

			if($thisEmployer)
			{
				$asEmployer = true;

				if ($advertEmployer === $thisEmployer->id)
				{
					$authorize = true;
				}else{
					$authorize = false;
				}
			}
		}

		if($advert->skills)
		{
			$skills = $advert->skills->implode('skill',',');
		}else{
			$skills = "";
		}

		// display "show" page
		return view('adverts.show', compact('advert','skills','authorize','asEmployer','user','done','notDone'));
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

		$dayName = new DailySchedule;

		if($user->ftu_level < 4)
		{
			// ftu level
			$done = 1;
        	$notDone = -1;
    	}else{
    		// advert level
    		$done = 0;
	        $notDone = -2;
    	}
		

		return view('adverts.create', compact('user','dayName','done','notDone'));
	}



	public function store(AdvertRequest $request)
	{
		$saveLater = $request->saveLater;
		$scheduleType = $request->scheduleType;
		$days = $request->day;
		$starts = $request->startDayTime;
		$ends = $request->endDayTime;
		//dd($days,$start,$end);

		if($saveLater != "true")
		{
			$this->validate($request, [
		        'job_title' => 'required|max:50',
		        'salary' => 'required|integer',
		        'rate' => 'required',
	            'description' => 'required',           
	            'location' => 'required',
	            'skills' => 'required',
	            'category' => 'required',
	    	]);

			switch($scheduleType)
			{
				case "specific":
					$this->validate($request, [
				        'startDate' => 'required|max:20',
				        'endDate' => 'required|max:20',
				        'startTime' => 'required|max:20',
			            'endTime' => 'required|max:20',           
			    	]);
			    	break;
			    case "daily":
				    if($days != ""){
				    	// $key => $value 
				    	// IS SAME AS 
				    	// [0] => $value OR 0 => 1
					    foreach($days as $key => $dayName)
						{
							$messages = [
							    'startDayTime.'.$key.'.required' => 'The Start At Field for '.$dayName.' is required',
							    'endDayTime.'.$key.'.required' => 'The Ends At Field for '.$dayName.' is required',
							];

							$this->validate($request, [
						        'startDayTime.'.$key => 'required|max:20',
					            'endDayTime.'.$key => 'required|max:20',           
					    	], $messages);
						}
						$this->validate($request, [
						        'dailyStartDate' => 'required|max:20',
					            'dailyEndDate' => 'required|max:20',           
					    ]);
					}else{
						$messages = [
							'day.required' => 'You need to choose the selected day when setting the time',
						];

						$this->validate($request, [
						    'day' => 'required|max:20',        
					    ], $messages);
					}
			    	break;
				
			    default:
			}
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
	        'schedule_type' => $request->scheduleType,
		]);

		switch($scheduleType)
		{
			case "specific":
				$advert->specificSchedule()->create([
					'start_date' => $request->startDate,
					'end_date' => $request->endDate,
					'start_time' => $request->startTime,
					'end_time' => $request->endTime,
				]);
				break;

			case "daily":
				foreach($days as $key => $dayName)
				{
					$dayName = DailySchedule::find($key);
					$advert->dailySchedule()->attach($dayName,[
							'start_time'=>$starts[$key],
							'end_time'=>$ends[$key]
						]);
				}
				$advert->update([
					'daily_start_date' => $request->dailyStartDate,
					'daily_end_date' => $request->dailyEndDate
				]);
				$advert->save();
				break;

			default:
		}

		$arrayOfSkills = explode(",",$request->skills);

		foreach($arrayOfSkills as $skill)
		{
			// convert string into lower case only
			$skill = strtolower($skill);

			// check if skill already exist in list
			if(count(Skill::where('skill',$skill)->get()) > 0)
			{	
				$skill = Skill::where('skill',$skill)->get();
				$advert->skills()->attach($skill);
			}else{
				$newSkill = new Skill;
				$newSkill->skill = $skill;
				$newSkill->save();
				$advert->skills()->attach($newSkill);
			}
		}

		switch ($saveLater)
		{
			case "true":
				$advert->ready_to_publish = 0;
				$advert->save();
				if($user->ftu_level < 4)
				{
					$user->ftu_level = 2;
					$user->save();
				}
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

		$skills = $advert->skills->implode('skill',',');
		$scheduleType = $advert->schedule_type;
		$dayName = new DailySchedule;
		$startDate = null;
		$endDate = null;
		$startTime = null;
		$endTime = null;
		$days = null;
		$dailyStart = null;
		$dailyEnd = null; 

		switch($scheduleType)
		{
			case 'specific':
				if($advert->specificSchedule)
				{
					$startDate = $advert->specificSchedule->start_date;
					$endDate = $advert->specificSchedule->end_date;
					$startTime = $advert->specificSchedule->start_time;
					$endTime = $advert->specificSchedule->end_date;
				}
				break;

			case 'daily':
				if($advert->dailySchedule)
				{
					$days = $advert->dailySchedule;
					$dailyStart = $advert->daily_start_date;
					$dailyEnd = $advert->daily_end_date; 
				}
				break;
			default:
		}
		
		// display "edit" page
		return view('adverts.edit', compact(
									'advert',
									'skills',
									'scheduleType',
									'dayName',
									'startDate',
									'endDate',
									'startTime',
									'endTime',
									'days',
									'dailyStart',
									'dailyEnd'));
	}



	/**
	 * Update existing advert
	 *
	 * @param $request, $id, $job_title
	 */
	public function update(AdvertRequest $request, Search $search, $id, $job_title)
	{
		$advert = Advert::locatedAt($id, $job_title)->firstOrFail();
		$saveLater = $request->saveLater;
		$scheduleType = $request->scheduleType;
		$days = $request->day;
		$starts = $request->startDayTime;
		$ends = $request->endDayTime;

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

	    	switch($scheduleType)
			{
				case "specific":
					$this->validate($request, [
				        'startDate' => 'required|max:20',
				        'endDate' => 'required|max:20',
				        'startTime' => 'required|max:20',
			            'endTime' => 'required|max:20',           
			    	]);
			    	break;
			    case "daily":
				    if($days != ""){
				    	// $key => $value 
				    	// IS SAME AS 
				    	// [0] => $value OR 0 => 1
					    foreach($days as $key => $dayName)
						{
							$messages = [
							    'startTime.'.$key.'.required' => 'The Start At Field for '.$dayName.' is required',
							    'endTime.'.$key.'.required' => 'The Ends At Field for '.$dayName.' is required',
							];

							$this->validate($request, [
						        'startDayTime.'.$key => 'required|max:20',
					            'endDayTime.'.$key => 'required|max:20',           
					    	], $messages);
						}
					}else{
						$messages = [
							'day.required' => 'You need to choose the selected day when setting the time',
						];

						$this->validate($request, [
						    'day' => 'required|max:20',        
					    ], $messages);
					}
			    	break;
			    default:
			}
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
		    'schedule_type' => $request->scheduleType,
		]);

		switch($scheduleType)
		{
			case "specific":
				if($advert->dailySchedule)
				{
					$advert->dailySchedule()->detach();

					$advert->update([
						'daily_start_date' => null,
						'daily_end_date' => null
					]);
					$advert->save();

				}

				if($advert->specificSchedule){

					$advert->specificSchedule()->update([
						'start_date' => $request->startDate,
						'end_date' => $request->endDate,
						'start_time' => $request->startTime,
						'end_time' => $request->endTime,
					]);
				}else{
					$advert->specificSchedule()->create([
						'start_date' => $request->startDate,
						'end_date' => $request->endDate,
						'start_time' => $request->startTime,
						'end_time' => $request->endTime,
					]);
				}
				break;

			case "daily":
				if($advert->specificSchedule)
				{
					$advert->specificSchedule()->delete();

				}elseif($advert->dailySchedule){

					$advert->dailySchedule()->detach();

					$advert->update([
						'daily_start_date' => null,
						'daily_end_date' => null
					]);
					$advert->save();
				}

				foreach($days as $key => $dayName)
				{
					$dayName = DailySchedule::find($key);
					$advert->dailySchedule()->attach($dayName,[
							'start_time'=>$starts[$key],
							'end_time'=>$ends[$key]
						]);
				}
				$advert->update([
					'daily_start_date' => $request->dailyStartDate,
					'daily_end_date' => $request->dailyEndDate
				]);
				$advert->save();
				break;

			default:
				if($advert->specificSchedule)
				{
					$advert->specificSchedule()->delete();

				}elseif($advert->dailySchedule){

					$advert->dailySchedule()->detach();

					$advert->update([
						'daily_start_date' => null,
						'daily_end_date' => null
					]);
				}
		}
		$advert->save();

		$arrayOfSkills = explode(",",$request->skills);

		if($advert->skills)
		{
			$advert->skills()->detach();
		}

		foreach($arrayOfSkills as $skill){
			// convert string into lower case only
			$skill = strtolower($skill);

			// check if skill already exist in list
			if(count(Skill::where('skill',$skill)->get()) > 0)
			{	
				$skill = Skill::where('skill',$skill)->get();
				$advert->skills()->attach($skill);
			}else{
				$newSkill = new Skill;
				$newSkill->skill = $skill;
				$newSkill->save();
				$advert->skills()->attach($newSkill);
			}
		}

		$config = config('services.algolia');
		$index = $config['index'];
		$indexFromAlgolia = $search->index($index);

		switch ($saveLater)
		{
			case "true":
				$advert->published = 0;
				$advert->save();
				$objectID = $advert->id;
				$object = $indexFromAlgolia->deleteObject($objectID);

				if($object)
				{
					flash('Changes made from your advert has been successfully saved but not yet published', 'info');
					return redirect('/adverts');
				}else{
					flash('Woops, looks like somethings went wrong. Please try again', 'error');
					return redirect()->back();
				}
				
				break;
			default:
				$advert->ready_to_publish = 1;
				$advert->save();

				$todaysDate = Carbon::now();
		        $planEndDate = $advert->plan_ends_at;
		        $daysLeft =  $todaysDate->diffInDays($planEndDate, false);

		        if($planEndDate === null){

		        	flash('You need to purchase a plan to published your job advert', 'info');

		            return redirect()->route('plan', [$advert->id]);

		        }elseif($daysLeft < 0){

		            flash('your package has been expired, please purchase a new plan', 'info');

		            return redirect()->route('plan', [$advert->id]);
		        }
		}

		$schedule = $advert->specificSchedule;
		$startDate = null;
		$endDate = null;
		$startTime = null;
		$endTime = null;
		$days = null;
		$dailyStart = null;
		$dailyEnd = null; 


		switch($scheduleType)
		{
			case 'specific':
				if($advert->specificSchedule)
				{
					$startDate = $advert->specificSchedule->start_date;
					$endDate = $advert->specificSchedule->end_date;
					$startTime = $advert->specificSchedule->start_time;
					$endTime = $advert->specificSchedule->end_date;
				}
				break;

			case 'daily':
				if($advert->dailySchedule)
				{
					$days = $advert->dailySchedule;
					$dailyStart = $advert->daily_start_date;
					$dailyEnd = $advert->daily_end_date; 
				}
				break;
			default:
		}

		$advert->published = 1;
		$advert->save();

		$object = $indexFromAlgolia->saveObject([
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
	        'category' => $advert->category,
	        'rate' => $advert->rate,
	        'oku_friendly' => $advert->oku_friendly,
	        'published' => $advert->published,
	        'avatar' => $advert->avatar,
	        'schedule_type' => $advert->schedule_type,
	        'start_date' => $startDate,
			'end_date' => $endDate,
			'start_time' => $startTime,
			'end_time' => $endTime,
			'daily_schedule' => $days,
			'daily_start_date' => $dailyStart,
			'daily_end_date' => $dailyEnd,
			'skills' => $arrayOfSkills,
			'group' => 'All',
			'objectID' => $advert->id,
		]);

		if($object)
		{
			flash('Your advert has been successfully published.', 'success');

			return redirect()->route('show', [$id,$advert->job_title]);

		}else{

			flash('There was something wrong when publishing your advert. Please try again.', 'error');

			return redirect()->back();
		}
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
			$scheduleType = $advert->scheduleType;
			$config = config('services.algolia');
			$index = $config['index'];
			$indexFromAlgolia = $search->index($index);
			$objectID = $advert->id;

			$scheduleType = $advert->schedule_type;
			$startDate = null;
			$endDate = null;
			$startTime = null;
			$endTime = null;
			$days = null;
			$dailyStart = null;
			$dailyEnd = null; 

			switch($scheduleType)
			{
				case 'specific':
					if($advert->specificSchedule)
					{
						$startDate = $advert->specificSchedule->start_date;
						$endDate = $advert->specificSchedule->end_date;
						$startTime = $advert->specificSchedule->start_time;
						$endTime = $advert->specificSchedule->end_date;
					}
					break;

				case 'daily':
					if($advert->dailySchedule)
					{
						$days = $advert->dailySchedule;
					}
					break;
				default:
			}

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
			        'published' => $advert->published,
			        'avatar'  => $advert->avatar,
			        'schedule_type' => $advert->schedule_type,
			        'start_date' => $startDate,
					'end_date' => $endDate,
					'start_time' => $startTime,
					'end_time' => $endTime,
					'daily_schedule' => $days,
					'daily_start_date' => $dailyStart,
					'daily_end_date' => $dailyEnd,
					'skills' => $advert->skills,
			        'group' => 'All',
			    ],
			    $objectID
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
			$objectID = $advert->id;
			$object = $indexFromAlgolia->deleteObject($objectID);

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
