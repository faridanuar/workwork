<?php

namespace App\Console;

use User;
use Advert;
use Carbon\Carbon;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         \App\Console\Commands\AlgoliaIndexer::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(
            function(){

               $users = User::where('type', 'employer')->get();

                foreach($users as $user)
                {
                    $todaysDate = Carbon::now();

                    $endDate = $user->ends_at;

                    $daysLeft =  $todaysDate->diffInDays($endDate, false);

                    if($daysLeft < 0)
                    {
                        $adverts = Advert::where('employer_id', $user->employer->id)->get();

                        foreach($adverts as $advert)
                        {
                            $advert->open = 0;

                            $advert->save();
                        }
                    }
                }
            }
        )->everyMinute();
    }
}
