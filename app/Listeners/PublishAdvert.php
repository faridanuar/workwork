<?php

namespace App\Listeners;

use App\Events\PostingAdvert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishAdvert
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  PostingAdvert  $event
     * @return void
     */
    public function handle(PostingAdvert $event)
    {
        $advert = $event->advert;

        $logo = $advert->employer->business_logo;
        $businessName = $advert->employer->business_name;

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

        $config = config('services.algolia');
        $index = $config['index'];
        $indexFromAlgolia = $event->search->index($index);

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
            'logo' => $logo,
            'schedule_type' => $advert->schedule_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'selected_days' => $days,
            'daily_start_date' => $dailyStart,
            'daily_end_date' => $dailyEnd,
            'skills' => $advert->skills,
            'group' => 'All',
            'objectID' => $advert->id,
        ]);
    }
}
