<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Mail;
use Services_Twilio;
use Image;
use Event;
use Cache;
use Session;

use App\User;
use App\Advert;
use App\Application;
use App\Skill;
use App\Employer;
use App\JobSeeker;
use App\JobSeekerRating;
use App\SpecificSchedule;
use App\DailySchedule;
use App\Activity;

use Carbon\Carbon;

use App\Events\PostingAdvert;
use App\Events\AdvertCaching;

use App\Http\Requests;

use App\Http\Requests\AdvertRequest;

use App\Contracts\Search;

class AdminController extends Controller
{
    /**
     * Create a new sessions controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function dashboard(Request $request)
    {
    	$user = $request->user();

    	// check if user's avatar exist & get the provided avatar
        $avatar = $user->currentAvatar();

        $adverts = Advert::where('employer_id', $user->employer->id)->orderBy('published', 'desc')->paginate(10);

    	return view('admin.admin_dashboard', compact('user', 'avatar', 'adverts'));
    }



    public function create()
    {
    	// declare a new DailySchedule for comparison
		$dayName = new DailySchedule;

		return view('admin.admin_create_advert', compact('dayName'));
    }



    public function store(AdvertRequest $request)
    {
    	$saveLater = $request->saveLater;

		$scheduleType = $request->scheduleType;

		$days = $request->day;

		if($saveLater != "true")
		{
			$this->validate($request, [
		        'job_title' => 'required|max:60',
		        'advert_from' => 'required|max:60',
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
				    if($days != "")
				    {
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

		if($request->oku_friendly != null)
		{
			$oku_friendly = "yes";

		}else{

			$oku_friendly = "no";
		}

		// what do we need to do? if the request validates, the body below of this method will be hit
		// validate the form - DONE		
		// persist the advert - DONE
		$advert = $user->employer->adverts()->create([
	        'job_title' => $request->job_title,
	        'advert_from' => $request->advert_from,
	        'salary'  => (float)$request->salary,
	        'description'  => $request->description,
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
	        'schedule_type' => $request->scheduleType,
	        'logo_from' => '/images/defaults/default.jpg'
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
				$starts = $request->startDayTime;

				$ends = $request->endDayTime;

				foreach($days as $key => $dayName)
				{
					$dayName = DailySchedule::find($key);

					$advert->dailySchedule()
							->attach($dayName,[
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

		$advert->current_plan = 'admin';

		switch ($saveLater)
		{
			case "true":

				$advert->ready_to_publish = 0;

				$advert->save();

				$user->save();

				flash('Your advert has been successfully saved but not yet published', 'info');

				return redirect('/a/dashboard');
				break;

			default:
				$advert->ready_to_publish = 1;

				if($advert->advert_level < 4)
				{
					$advert->advert_level = 1;
				}
				
				$advert->save();

				$user->save();
		}

		$activity = new Activity;

		$activity->create([
				'activity' => 'create',
				'description' => 'A new advert was created name: ' . $advert->job_title,
				'table' => 'adverts',
				'user' => $user->name
			]);

		return redirect()->route('logo', [$advert->id, $advert->job_title]);
    }



    public function logo(Request $request, $id, $job_title)
    {
        $user = $request->user();

        $advert = Advert::locatedAt($id, $job_title)->firstOrFail();

        $logo = $advert->logo_from;

        //check if photo path exist
        if($logo != "" && $logo != null && $logo != "/images/defaults/default.jpg")
        {
            $fileExist = true;

            $photo = $logo;

        }else{

            $fileExist = false;

            $photo = "/images/defaults/default.jpg";
        }

        return view('admin.admin_advert_logo', compact('photo', 'advert', 'fileExist'));
    }



    protected function uploadLogo(Request $request, Search $search, $id, $job_title)
    {
        $this->validate($request, [

            'photo' => 'required|mimes:jpg,jpeg,png,bmp' // validate image
        ]);

        $advert = Advert::locatedAt($id, $job_title)->firstOrFail();

        // fetch photo
    	$file = $request->file('photo');

        // set uploaded photo name into a unique name
    	$name = time(). '-' .$file->getClientOriginalName();

        // set file directory for photo to be moved
    	$path = "images/profile_images/logo";

        // compress, save and move the photo to the given path
        Image::make($file)->fit(200, 200)->save($path."/".$name);

        // get the new created photo directory path
        $pathURL = "/".$path."/".$name;

        // save the new photo directory path into the database
    	$advert->logo_from = $pathURL;

        $advert->save();

        if($advert->published === 1)
        {
        	// filter and fetch live/published adverts only
	        $liveAdvert = $advert;

	        // fetch data from config.php
	        $config = config('services.algolia');

	        // provide index
	        $index = $config['index'];

	        // select algolia index/indice name
	        $indexFromAlgolia = $search->index($index);

	        // update algolia existing object. Determine which by row id
            $indexFromAlgolia->partialUpdateObject([
                'logo' => $pathURL,
                'objectID' => $liveAdvert->id,
            ]);
        }

        Event::fire(new AdvertCaching($advert));

        $activity = new Activity;

        $user = $request->user();

		$activity->create([
				'activity' => 'upload logo',
				'description' => 'A new logo was added to: ' . $advert->job_title,
				'table' => 'adverts',
				'user' => $user->name
			]);
    }



    public function removeLogo(Request $request, $id, Search $search)
    {
        $advert = Advert::findOrFail($id);

        $advert->logo_from = "/images/defaults/default.jpg";

        $advert->save();

        if($advert->published === 1)
        {
        	// provide path URl for Database
	        $pathURL = "/images/defaults/default.jpg";

	        // fetch published adverts only
	        $liveAdvert = $advert;

	        //fetch data from config.php
	        $config = config('services.algolia');

	        // provide index
	        $index = $config['index'];

	        // select algolia index/indice name
	        $indexFromAlgolia = $search->index($index);

	        // update algolia existing object. Determine which by row id
            $indexFromAlgolia->partialUpdateObject([
                'logo' => $pathURL,
                'objectID' => $liveAdvert->id,
            ]);
        }

        Event::fire(new AdvertCaching($advert));

        flash('Your photo has been successfully removed', 'success');

        return redirect()->back();
    }



    /**
    * Edit created resource in storage
    *
    * @param $request, $id, $job_title
    */
	public function edit(Request $request, $id, $job_title)
	{
		$user = $request->user();

		// display only the first retrieved
		$advert = Advert::locatedAt($id, $job_title)->firstOrFail();

		//perform this if user does not own this advert
		/*
		if(!$advert->ownedBy($user))
		{
			return $this->unauthorized($request);
		}
		*/

		if($advert->employer->user->id != $user->id)
		{
			if($request->ajax())
			{
				return response(['message' => 'No!'], 403);
			}

			flash('not the owner','error');

			return redirect('/');
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
		return view('admin.admin_edit_advert', compact('advert', 'skills', 'scheduleType', 'dayName', 'startDate', 'endDate', 'startTime', 'endTime', 'days', 'dailyStart', 'dailyEnd'));
	}



   /**
    * Update existing advert
    *
    * @param $request, $id, $job_title
    */
	public function update(AdvertRequest $request, Search $search, $id, $job_title)
	{
		$user = $request->user();

		$advert = Advert::locatedAt($id, $job_title)->firstOrFail();

		$saveLater = $request->saveLater;

		$scheduleType = $request->scheduleType;

		$days = $request->day;

		if($saveLater != true)
		{
			$this->validate($request, [
		        'job_title' => 'required|max:60',
		        'advert_from' => 'required|max:60',
		        'salary' => 'required|integer',
	            'description' => 'required',           
	            'location' => 'required',
	            'country' => 'required',
	            'category' => 'required',
	            'rate' => 'required',
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
				    if($days != "")
				    {
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

		if($request->oku_friendly != null)
		{
			$oku_friendly = "yes";

		}else{

			$oku_friendly = "no";
		}

		$advert->update([
			'job_title' => $request->job_title,
			'advert_from' => $request->advert_from,
			'salary' => (float)$request->salary,
			'description' => $request->description,
			'location' => $request->location,
			'street' => $request->street,
			'city' => $request->city,
			'zip' => $request->zip,
			'state' => $request->state,
			'country' => $request->country,
		    'category'  => $request->category,
		    'rate'  => $request->rate,
		    'oku_friendly'  => $oku_friendly,
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
				}

				$starts = $request->startDayTime;

				$ends = $request->endDayTime;

				foreach($days as $key => $dayName)
				{
					$dayName = DailySchedule::find($key);

					$advert->dailySchedule()
							->attach($dayName,[
								'start_time'=>$starts[$key],
								'end_time'=>$ends[$key]
							]);
				}

				$advert->update([
					'daily_start_date' => $request->dailyStartDate,
					'daily_end_date' => $request->dailyEndDate
				]);
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

		if($advert->skills)
		{
			$advert->skills()->detach();
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

		$config = config('services.algolia');

		$index = $config['index'];

		$indexFromAlgolia = $search->index($index);

		switch ($saveLater)
		{
			case "true":
				$advert->ready_to_publish = 0;

				$advert->published = 0;

				$advert->save();

				$objectID = $advert->id;

				$object = $indexFromAlgolia->deleteObject($objectID);

				if($object)
				{
					flash('Changes made from your advert has been successfully saved but not yet published', 'info');

					return redirect('/a/dashboard');

				}else{

					flash('Woops, looks like somethings went wrong. Please try again', 'error');
					
					return redirect()->back();
				}
				break;

			default:
				$advert->ready_to_publish = 1;

				$advert->save();


				/*
				$todaysDate = Carbon::now();
		        $planEndDate = $advert->plan_ends_at;
		        $daysLeft =  $todaysDate->diffInDays($planEndDate, false);

		        if($planEndDate === null)
		        {
		        	flash('You need to purchase a plan to published your job advert', 'info');

		            return redirect()->route('plan', [$advert->id]);

		        }elseif($daysLeft <= 0){

		            flash('your package has been expired, please purchase a new plan', 'info');

		            return redirect()->route('plan', [$advert->id]);

		        }else{
		        */

	        	$advert->published = 1;

				$advert->advert_level = 4;

				$advert->save();

				Event::fire(new PostingAdvert($advert, $search));

				/*
				if($user->ftu_level < 5)
				{
					$user->ftu_level = 5;
					$user->save();
				}
				*/
				
				Event::fire(new AdvertCaching($advert));

				$activity = new Activity;

				$activity->create([
					'activity' => 'upload logo',
					'description' => 'Advert '. $advert->job_title . 'was updated',
					'table' => 'adverts',
					'user' => $user->name
				]);

				flash('Your advert has been successfully published.', 'success');

				return redirect()->route('show', [$id,$advert->job_title]);
		}
	}



	/**
    * Store a newly created resource in storage
    *
    * @param AdvertRequest $request
    */
	public function publish(Request $request, Search $search)
	{
		$user = $request->user();

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

        /*
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
        */

		$advert->published = 1;

		$advert->advert_level = 4;

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
			        'business_name'  => $advert->advert_from,
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
			        'logo'  => $advert->logo_from,
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

			Event::fire(new AdvertCaching($advert));

			if($object)
			{
				$activity = new Activity;

				$activity->create([
					'activity' => 'publish advert',
					'description' => 'Advert '. $advert->job_title . ' was published',
					'table' => 'adverts',
					'user' => $user->name
				]);

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


   /**
	* Unpublish advert
	*
	*
	*/
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

			Event::fire(new AdvertCaching($advert));

			if($object)
			{
				$user = $request->user();

				$activity = new Activity;

				$activity->create([
					'activity' => 'unpublish advert',
					'description' => 'Advert '. $advert->job_title . ' was unpublished',
					'table' => 'adverts',
					'user' => $user->name
				]);

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



	public function owner(Request $request, $id, $job_title)
	{
		$business_name = $request->input('business_name');

		$advert = Advert::find($id);

		if($advert->employer->user->type != "admin")
		{
			return redirect('/a/dashboard');
		}

		if(!$business_name)
		{
			$business_name = "";
		}

		$employers = Employer::where('business_name', $business_name)->get();

		return view('admin.advert_change_owner', compact('employers', 'advert'));
	}



	public function changeOwner(Request $request, Search $search, $id, $job_title)
	{
		$this->validate($request, [
				'company_id' => 'required'
			]);

		$companyID = $request->company_id;

		$currentEmployerID = $request->user()->employer->id;

		$employer = Employer::findOrFail($companyID);

		$advert = Advert::findOrFail($id);

		$applications = Application::where('employer_id', '=', $currentEmployerID);

		foreach( $applications as $application )
		{
			$application->employer_id = $employer->id;

			$application->save();
		}

		$advert->employer_id = $employer->id;

		$advert->advert_from = null;

		$advert->logo_from = null;

		$plan = "1_month_plan";

		$days = 30;

		$advert->current_plan = $plan;

		$todaysDate = Carbon::now();
        $endDate = $advert->plan_ends_at;
        $expDate = $todaysDate->diffInDays($endDate, false);

        if($expDate == 0)
        {
            $advert->plan_ends_at = Carbon::now()->addDays($days);

        }else{

        	$advert->plan_ends_at = $endDate->addDays($days);
        }

        $advert->about_to_expire = 0;

		$advert->save();

		if($advert->published === 1)
		{
			$config = config('services.algolia');

			$index = $config['index'];

			$indexFromAlgolia = $search->index($index);

			Event::fire(new AdvertCaching($advert));

			Event::fire(new PostingAdvert($advert, $search));
		}

		$user = $request->user();

		$activity = new Activity;

		$activity->create([
			'activity' => 'change owner',
			'description' => 'The owner for advert '. 
								$advert->job_title . 
								'has been change from '. 
								$user->name . 
								' to an employer named '.
								$employer->name,
			'table' => 'adverts',
			'user' => $user->name
		]);

		flash('The owner of the advert has been changed!', 'success');

		return redirect('/a/dashboard');
	}



    public function allList($id)
    {
        $advert = Advert::find($id);

        $allInfos = Application::where('advert_id', $id)->paginate(5);

        return view('admin.admin_all_job_requests', compact('allInfos', 'id', 'advert'));
    }



    public function pendingList($id)
    {
        $advert = Advert::find($id);

        $requestInfos = Application::where('advert_id', $id)->where('status', 'PENDING')->paginate(5);
        
        return view('admin.admin_pending_job_requests', compact('requestInfos', 'id', 'advert'));
    }



    public function rejectedList($id)
    {
        $advert = Advert::find($id);

        $rejectedInfos = Application::where('advert_id', $id)->where('status', 'REJECTED')->paginate(5);

        return view('admin.admin_rejected_job_requests', compact('rejectedInfos', 'id', 'advert'));
    }



    public function acceptedList($id)
    {
        $advert = Advert::find($id);

        $acceptedInfos = Application::where('advert_id', $id)->where('status', 'ACCEPTED FOR INTERVIEW')->paginate(5);

        return view('admin.admin_accepted_job_requests', compact('acceptedInfos', 'id', 'advert'));
    }



    public function applicantInfo(Request $request, $id, $application_id)
    {
        $advert = Advert::find($id);

        $user = $request->user();

        //check if job advert is own by user
        /*
        if(!$advert->ownedBy($user))
        {
            return $this->unauthorized($request);
        }
        */

        if($advert->employer->user->id != $user->id)
        {
            if($request->ajax())
            {
                return response(['message' => 'No!'], 403);
            }
            flash('not the owner','error');
            return redirect('/');
        }

        $allAdverts = $user->employer->adverts->where('id', '<=', $advert->id);

        $application = Application::find($application_id);

        $jobSeeker = JobSeeker::find($application->job_seeker_id);

        $employer = Employer::find($application->employer->id);

        $ratings = $jobSeeker->ownRatings->count();

        $rated = false;

        $haveRating = JobSeekerRating::where('job_seeker_id', $jobSeeker->id)->where('employer_id', $employer->id )->first();

        $photo = $jobSeeker->user->currentAvatar();
        
        if($ratings === 0)
        {
            $average = 0;

        }else{

            $average = $jobSeeker->ownRatings->avg('rating');
        }

        if($haveRating)
        {
            if($haveRating->employer_id === $employer->id)
            {
                $rated = true;
            } 
        }

        return view('admin.admin_applicant_info', compact('id','photo','jobSeeker','rated','average','ratings','application'));
    }



    public function response(Request $request, $application_id)
    {
        $application = Application::find($application_id);

        $recipientName = $application->jobSeeker->user->name;

        $contact = $application->jobSeeker->user->contact;

        $status = $request->status;

        if($status != "REJECTED"){

            $this->validate($request, [
                'status' => 'required|max:50',
                'arrangement' => 'required',
            ]);

            $comment = $request->arrangement;

        }else{

            $this->validate($request, [
                'status' => 'required|max:50',
                'feedback' => 'required',
            ]);

            $comment = $request->feedback;
        }

        $application->update([
            'status' => $request->status,
            'employer_comment' => $comment,
        ]);

        $application->responded = 1;

        if($application->save())
        {
            $config = config('services.twilio');

            // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
            $AccountSid = $config['acc_id'];

            $AuthToken = $config['auth_token'];

            $websiteURL = $config['site_url'];

            $url = $websiteURL."my/applications/$application_id";

            $job_title = $application->advert->job_title;

            $contact = $application->jobSeeker->user->contact;

            $JobSeekerName = $application->jobSeeker->user->name;

            if($application->jobSeeker->user->contact_verified != 0)
            {
                if($application->jobSeeker->user->contact)
                {

                    // Step 3: instantiate a new Twilio Rest Client
                    $client = new Services_Twilio($AccountSid, $AuthToken);

                    // Step 4: make an array of people we know, to send them a message. 
                    // Feel free to change/add your own phone number and name here.
                    $people = array(
                        //"+60176613069" => $recipientName,
                        "+6".$contact => $JobSeekerName,
                        //"+14158675310" => "Boots",
                        //"+14158675311" => "Virgil",
                    );
                    
                    // Step 5: Loop over all our friends. $number is a phone number above, and 
                    // $name is the name next to it
                    foreach ($people as $number => $name) {

                        $sms = $client->account->messages->sendMessage(

                            // Step 6: Change the 'From' number below to be a valid Twilio number 
                            // that you've purchased, or the (deprecated) Sandbox number
                            "+12602184571", 

                            // the number we are sending to - Any phone number
                            $number,

                            // the sms body
                            "Your request job for $job_title has been responded, full details here: $url ."
                        );
                        // Display a confirmation message on the screen
                        //echo "Sent message to $name";
                    }
                }
            }

            // testing recipient email => $recipient = "farid@pocketpixel.com";
            $config = config('services.mailgun');

            $domain = $config['sender'];

            $recipient = $application->jobSeeker->user->email;

            $recipientName = $application->jobSeeker->user->name;

            $emailView = 'mail.application_notification';

            $data = [
                        'websiteURL' => $websiteURL,
                        'application' => $application
                    ];

            $parameter = [
                            'domain' => $domain,
                            'recipient' => $recipient,
                            'recipientName' => $recipientName
                         ];

            if($application->jobSeeker->user->verified != 0)
            {
                if($application->jobSeeker->user->email)
                {
                    // use send method from Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
                    Mail::send($emailView, $data, function ($message) use ($parameter) {

                        // provide sender domain and sender name
                        $message->from($parameter['domain'], 'WorkWork');

                        // provide recipient email, recipient name and email subject
                        $message->to($parameter['recipient'], $parameter['recipientName'])->subject('Application Notification');
                    });
                }
            }

            $activity = new Activity;

			$activity->create([
				'activity' => 'respond job request',
				'description' => 'Job request for '.
								 $application->job_title . 
								 'from Job Seeker named ' . 
								 $application->jobSeeker->user->name . 
								 ' has been responded',
				'table' => 'adverts',
				'user' => $user->name
			]);

            // set flash attribute and key. example --> flash('success message', 'flash_message_level')
            flash('Your response has been sent', 'success');

        }else{

            // set flash attribute and key. example --> flash('success message', 'flash_message_level')
            flash('Uh Oh, something went wrong when saving your response. Please try again later.', 'error');

            return redirect()->back();
        }

        return redirect()->back();
    }



    public function history(Request $request)
    {
    	$activities = Activity::orderBy('id', 'desc')->paginate(10);

    	return view('admin.admin_activity_history', compact('activities'));
    }


}
