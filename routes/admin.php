<?php
// admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
	Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@dashboard']);
	Route::resource('nonprofits', 'Admin\NonprofitsController', [
		'names' => [
			'index' => 'admin.nonprofits.index',
			'edit' => 'admin.nonprofits.edit',
			'update' => 'admin.nonprofits.update',
		],
		'only' => ['index', 'edit', 'update']
	]);
	Route::resource('forprofits', 'Admin\ForprofitsController', [
		'names' => [
			'index' => 'admin.forprofits.index',
			'edit' => 'admin.forprofits.edit',
			'update' => 'admin.forprofits.update',
		],
		'only' => ['index', 'edit', 'update']
	]);
	Route::resource('volunteers', 'Admin\VolunteersController', [
		'names' => [
			'index' => 'admin.volunteers.index',
			'edit' => 'admin.volunteers.edit',
			'update' => 'admin.volunteers.update',
		],
		'only' => ['index', 'edit', 'update']
	]);
});