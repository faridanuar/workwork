<?php

use App\Advert;
// use Illuminate\Support\Facades\DB;

use App\Contracts\Search;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;


class AdvertsTableSeeder extends Seeder
{
    private $search;

    public function __construct(Search $search) {
        $this->search = $search;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1,10) as $index) {
            $advert = Advert::create([
                'employer_id'   => 1,
                'job_title'     =>  $faker->jobTitle,
                'location'      =>  $faker->streetName,
                'street'        =>  $faker->streetAddress,
                'city'          =>  $faker->city,
                'zip'           =>  $faker->postcode,
                'state'         =>  $faker->state,
                'country'       =>  $faker->country,
                'salary'        =>  $faker->numberBetween(10,100),
                'published'     => 1,
                'description'   =>  $faker->realText($maxNbChars = 600, $indexSize = 2),
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            $scheduleType = $advert->scheduleType;
			$config = config('services.algolia');
			$index = $config['index'];
			$indexFromAlgolia = $this->search->index($index);
			$objectID = $advert->id;

			$scheduleType = $advert->schedule_type;
			$startDate = null;
			$endDate = null;
			$startTime = null;
			$endTime = null;
			$days = null;
			$dailyStart = null;
			$dailyEnd = null; 

            $object = $indexFromAlgolia->addObject(
				[
			    	'id' => $advert->id,
			        'job_title' => $advert->job_title,
			        'salary'  => (float)$advert->salary,
			        'description'  => $advert->description,
			        'business_name'  => $advert->employer->business_name,
			        'location'  => $advert->location,
			        'street'  => $advert->street,
			        'city'  => $advert->city,
			        'zip'  => $advert->zip,
			        'state'  => $advert->state,
			        'country'  => $advert->country,
			        'created_at'  => $advert->created_at->toDateTimeString(),
			        'updated_at'  => $advert->updated_at->toDateTimeString(),
			        'employer_id'  => $advert->employer_id,
			        'category'  => $advert->category,
			        'rate'  => $advert->rate,
			        'oku_friendly'  => $advert->oku_friendly,
			        'published' => 1,
			        'logo'  => $advert->employer->business_logo,
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
			    ],
			    $objectID
			);
        }
    }
}
