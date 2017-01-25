<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Contracts\Search;

use App\Advert;

class AlgoliaIndexer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workwork:index {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $table = $this->argument('table');

        $collection = collect(

            \DB::table($table)->get()

        )->map(function($item) {

            $item->objectID = $item->id;

            return (array) $item;

        });

        $search->index($table)->saveObjects($collection);
        
        $this->info('All Done!');

    }
}
