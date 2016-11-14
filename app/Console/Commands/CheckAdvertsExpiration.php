<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Advert;

use App\Contracts\Search;

class CheckAdvertsExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adverts:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking all adverts plan expiration date validity';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Search $search)
    {
        $adverts = Advert::all();

        foreach($adverts as $advert)
        {
            $todaysDate = Carbon::now();
            $endDate = $advert->plan_ends_at;
            $expDate =  $todaysDate->diffInDays($endDate, false);

            if($expDate < 0){

                $advert->published = 0;

                $advert->save();

                $config = config('services.algolia');

                $index = $config['index'];

                $indexFromAlgolia = $search->index($index);

                $object = $indexFromAlgolia->deleteObject($advert->id);

            }
        }

        $this->info('All Checked & Validated!');
    }
}
