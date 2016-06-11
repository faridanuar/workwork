<?php

use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;


class AdvertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1,10) as $index) {
            DB::table('adverts')->insert([
                'job_title'     =>  $faker->jobTitle,
                'business_name' =>  $faker->company,
                'location'      =>  $faker->streetName,
                'street'        =>  $faker->streetAddress,
                'city'          =>  $faker->city,
                'zip'           =>  $faker->postcode,
                'state'         =>  $faker->state,
                'country'       =>  $faker->country,
                'salary'        =>  $faker->numberBetween(10,100),
                // 'description'    =>  $faker->paragraphs(5),
                'business_logo' =>  $faker->imageUrl($width = 200, $height = 200, 'abstract'),
                'description'   =>  $faker->realText($maxNbChars = 600, $indexSize = 2),
            ]);
        }
    }
}
