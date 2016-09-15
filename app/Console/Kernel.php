<?php

namespace App\Console;

use Carbon\Carbon;
use App\Advert;
use App\Contracts\Search;


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
            function(Search $search)
            {
                $adverts = Advert::all();

                foreach($adverts as $advert)
                {
                    $todaysDate = Carbon::now();

                    $endDate = $advert->plan_ends_at;

                    $daysLeft =  $todaysDate->diffInDays($endDate, false);

                    if($daysLeft < 0){

                        $advert->published = 0;

                        $advert->save();

                        $config = config('services.algolia');

                        $index = $config['index'];

                        $indexFromAlgolia = $search->index($index);

                        $object = $indexFromAlgolia->deleteObject($advert->id);
                    }
                }
            }
        )->everyMinute();
    }
}
