<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Advert;
use App\Application;

class FillApplicationsMissingJobTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applications:filljob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill all applications missing advert job title';

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
    public function handle()
    {
        $applications = Application::all();

        foreach( $applications as $application )
        {
            if($application->advert_job_title === null)
            {
                $application->advert_job_title = $application->advert->job_title;

                $application->save();
            }
        }

        $this->info('All Advert Job Title is Checked & Filled!');
    }
}
