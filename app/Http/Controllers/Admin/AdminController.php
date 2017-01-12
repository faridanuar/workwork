<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\DailySchedule;

use App\Http\Requests;

use App\Http\Requests\AdvertRequest;

class AdminController extends Controller
{
    /**
     * Create a new sessions controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function dashboard(Request $request)
    {
    	$user = $request->user();

    	// check if user's avatar exist & get the provided avatar
        $avatar = $user->currentAvatar();

    	return view('admin.admin_dashboard', compact('user', 'avatar'));
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
		        'business_name' => 'required|max:60',
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
	        'business_name' => $request->business_name,
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

		return redirect()->route('plan', [$advert->id]);
    }



    public function history()
    {
    	return view('admin.admin_activity_history');
    }


}
