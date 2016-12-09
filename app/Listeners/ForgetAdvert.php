<?php

namespace App\Listeners;

use App\Events\AdvertCaching;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Cache;

class ForgetAdvert
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
     * @param  AdvertCaching  $event
     * @return void
     */
    public function handle(AdvertCaching $event)
    {
        Cache::forget('advert_'.$event->advert->id.'');
    }
}
