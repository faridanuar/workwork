<?php

namespace App\Listeners;

use App\Events\PostingAdvert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Contracts\Search;

class PublishAdvert
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostingAdvert  $event
     * @return void
     */
    public function handle(PostingAdvert $event, Search $search)
    {
        $advert = $event;
        $scheduleType = $advert->schedule_type;
        $startDate = null;
        $endDate = null;
        $startTime = null;
        $endTime = null;
        $days = null;
        $dailyStart = null;
        $dailyEnd = null; 
        $scheduleType = $advert->scheduleType;
        $businessName = $advert->employer->business_name;
        $avatar = $advert->employer->user->avatar;
        
        $config = config('services.algolia');
        $index = $config['index'];
        $indexFromAlgolia = $search->index($index);
        $objectID = $advert->id;

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

        $indexFromAlgolia->saveObject([
            'id' => $advert->id,
            'job_title' => $advert->job_title,
            'salary' => (float)$advert->salary,
            'description' => $advert->description,
            'business_name' => $businessName,
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
            'avatar' => $avatar,
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
            'objectID' => $advert->id,
        ]);
    }
}
