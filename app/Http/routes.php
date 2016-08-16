<?php

// Route::get('/', 'PagesController@home');

// Route::get('/launch', 'PagesController@launch');

Route::auth();

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');

Route::get('/avatar', 'HomeController@avatar');

Route::post('/upload-avatar', 'HomeController@uploadAvatar');

/**
 * Social routes
 */
Route::get('/redirect', 'SocialAuthController@redirect');

Route::get('/callback', 'SocialAuthController@callback');

/**
 * Assign Roles routes
 */
Route::get('/choose', 'TypeController@choose');

Route::post('/set', 'TypeController@assignType');

/**
 * Company Profile routes
 */
Route::get('/company/create', 'CompanyProfController@create');

Route::post('/company/store', 'CompanyProfController@store');

Route::get('/company/{id}/{business_name}', 'CompanyProfController@profile')->name('company');

Route::get('/company/edit', 'CompanyProfController@edit');

Route::post('/company/update', 'CompanyProfController@update');

Route::get('/logo', 'CompanyProfController@logo');

Route::post('/upload/logo', 'CompanyProfController@uploadLogo');

Route::get('/company/{id}/{business_name}/review', 'CompanyProfController@companyReview');

Route::get('/my/adverts', 'CompanyProfController@myAdvert');

Route::get('/advert/{id}/job/requests', 'CompanyProfController@jobRequest');

Route::post('/advert/job/requests/{id}/response', 'CompanyProfController@response');

Route::post('/profile/{id}/{user_id}/rate', 'CompanyProfController@rate');

/**
 * Job Seeker Profile routes
 */
Route::get('/profile/create', 'JobSeekerProfController@create');

Route::post('/profile/store', 'JobSeekerProfController@store');

Route::get('/profile/{id}/{user_id}', 'JobSeekerProfController@profileInfo')->name('jobSeeker');

Route::get('/profile/edit', 'JobSeekerProfController@edit');

Route::post('/profile/edit/update', 'JobSeekerProfController@update');

Route::get('/profile/{id}/{user_id}/review', 'JobSeekerProfController@jobSeekerReview');

Route::post('/company/{id}/{business_name}/rate', 'JobSeekerProfController@rate');

/**
 * Adverts routes
 */
Route::resource('adverts', 'AdvertsController');

Route::get('/adverts/{id}/{job_title}', 'AdvertsController@show')->name('show');

Route::post('adverts/preview', 'AdvertsController@preview');

/**
 * Adverts edit routes
 */
Route::get('adverts/{id}/{job_title}/edit', 'AdvertsController@edit');

Route::post('adverts/{id}/{job_title}/edit/update', 'AdvertsController@update');

/**
* Job Seeker Apply Advert routes
*/
Route::get('/adverts/{id}/{job_title}/apply', 'ApplyController@apply');

Route::post('/adverts/{id}/{job_title}/apply/add', 'ApplyController@storeApply');

/**
* Subcription routes
*/
Route::get('/plans', 'SubscribeController@plans');

Route::get('/subscribe', 'SubscribeController@subscribe');

Route::post('/checkout', 'SubscribeController@checkout');

Route::get('/invoices', 'SubscribeController@invoices');

Route::get('/invoices/download/{invoiceId}', 'SubscribeController@download');

/**
* Webhook routes
*/
Route::post('braintree/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');

/**
* Payment routes
*/
Route::get('/status', 'StatusController@status');

Route::get('/cancel', 'StatusController@cancel');

Route::get('/resume', 'StatusController@resume');


