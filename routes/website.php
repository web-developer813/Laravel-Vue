<?php
// website
Route::group(['namespace' => 'Website'], function() {
	Route::get('/', ['as' => 'home', 'uses' => 'PagesController@home']);
	Route::post('/pusher/status/',['as' => 'user.status', 'uses' => 'StatusController@post']);
});