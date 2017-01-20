<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Image;
use Event;
use Cache;
use Session;

use App\User;
use App\Advert;
use App\Skill;
use App\Employer;
use App\SpecificSchedule;
use App\DailySchedule;

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

        $adverts = Advert::where('employer_id', $user->employer->id)->get();

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

		if($request->oku_friendly != null){
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

        // fetch advert's created adverts
        $adverts = Advert::where('advert_from', '=', $advert->advert_from);

        // filter and fetch live/published adverts only
        $liveAdverts = $adverts->where('published', 1)->get();

        //fetch data from config.php
        $config = config('services.algolia');

        // provide index
        $index = $config['index'];

        // select algolia index/indice name
        $indexFromAlgolia = $search->index($index);

        // loop algolia object update for each row
        foreach($liveAdverts as $liveAdvert)
        {
            // update algolia existing object. Determine which by row id
            $object = $indexFromAlgolia->partialUpdateObject([
                'logo' => $pathURL,
                'objectID' => $liveAdvert->id,
            ]);
        }
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
					$advert->save();
				}

				$starts = $request->startDayTime;
				$ends = $request->endDayTime;

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
				'business_id' => 'required'
			]);

		$businessID = $request->business_id;

		$employer = Employer::findOrFail($businessID);

		$advert = Advert::findOrFail($id);

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

		flash('The owner of the advert has been changed!', 'success');

		return redirect('/a/dashboard');
	}



    public function history()
    {
    	return view('admin.admin_activity_history');
    }


}
