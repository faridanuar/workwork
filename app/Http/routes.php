<?php

// Route::get('/', 'PagesController@home');

// Route::get('/launch', 'PagesController@launch');



Route::auth();

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');

Route::get('/redirect', 'SocialAuthController@redirect');

Route::get('/callback', 'SocialAuthController@callback');

/**
 * Adverts routes
 */
Route::resource('adverts', 'AdvertsController');

Route::get('/adverts/{id}/{job_title}', 'AdvertsController@show');

/**
 * Adverts routes flash message
 */
Route::get('stage', function() {

	session()->flash('status', 'Here is my status');

	return redirect('stage');
});

/**
* Advert's Job Seeker Application
*/
Route::get('/adverts/{id}/{job_title}/apply', 'ApplyController@apply');

Route::post('/adverts/{id}/{job_title}/apply/job_seeker', 'ApplyController@store_apply');