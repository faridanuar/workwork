<?php



// Route::get('about', 'PagesController@about');
//
// Route::get('contact', 'PagesController@contact');


Route::get('/', 'PagesController@launch');

Route::post('/newsletter/job-seeker', [
    'as' => 'newsletter.create',
    'uses' => 'NewsletterController@create',
]);
