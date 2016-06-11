<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

/**
 * The blueprint for the advert... use for testing purposes or seeding purposes
 * Uses faker to generate dummy data https://github.com/fzaninotto/Faker
 */
$factory->define(App\Advert::class, function (Faker\Generator $faker) {
    return [		
		'job_title'		=>	$faker->jobTitle,
		'business_name'	=>	$faker->company,
		'location'		=>	$faker->streetName,
		'street'		=>	$faker->streetAddress,
		'city'			=>	$faker->city,
		'zip'			=>	$faker->postcode,
		'state'			=>	$faker->state,
		'country'		=>	$faker->country,
		'salary'		=>	$faker->numberBetween(10,100),
		// 'description'	=>	$faker->paragraphs(5),
		'business_logo'	=>	$faker->imageUrl($width = 200, $height = 200, 'abstract'),
		'description'	=>	$faker->realText($maxNbChars = 600, $indexSize = 2),
		
           
    ];
});