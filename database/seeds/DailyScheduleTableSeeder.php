<?php

use Illuminate\Database\Seeder;

class DailyScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('daily_schedules')->insert([
        	[
	            'day' => 'Monday',
            ],
            [
            	'day' => 'Tuesday',
            ],
            [
            	'day' => 'Wednesday',
            ],
            [
            	'day' => 'Thursday',
            ],
            [
            	'day' => 'Friday',
            ],
            [
            	'day' => 'Saturday',
            ],
            [
            	'day' => 'Sunday',
            ],
        ]);
    }
}
