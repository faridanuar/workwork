<?php

namespace App\Listeners;

use App\Events\UserCaching;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgetUser
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
     * @param  UserCaching  $event
     * @return void
     */
    public function handle(UserCaching $event)
    {
        Cache::forget('advert'.$event->advert->id.'');
    }
}
