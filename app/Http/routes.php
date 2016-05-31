<?php

Route::get('/', 'PagesController@home');

Route::get('/launch', 'PagesController@launch');

Route::resource('adverts', 'AdvertsController');
