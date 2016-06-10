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