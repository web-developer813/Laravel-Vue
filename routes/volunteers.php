<?php
// auth & welcome done
Route::group(['middleware' => ['auth', 'welcome'], 'namespace' => 'Volunteers'], function() {
	
	// dashboard
	Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@dashboard']);
	
	// connections
	Route::get('connections', ['as'=> 'user.connections', 'uses' => 'ConnectionsController@index']);

	// opportunities
	Route::resource('opportunities', 'OpportunitiesController');

	// applications
	Route::post('opportunities/{opportunity}/apply', [
		'as' => 'opportunities.apply', 'uses' => 'ApplicationsController@store']);
	Route::get('applications', [
		'as' => 'applications.index', 'uses' => 'ApplicationsController@index']);
	Route::get('applications/{application}', [
		'as' => 'volunteers.applications.show', 'uses' => 'ApplicationsController@show']);

	// nonprofits
	Route::resource('nonprofits', 'NonprofitsController', ['only' => ['index', 'show', 'create', 'store']]);

	Route::get('nonprofits/{nonprofit}/donations', [
		'as' => 'nonprofits.show.donations',
		'uses' => 'NonprofitsController@donations'
	]);

	Route::get('nonprofits/{nonprofit}/about', [
		'as' => 'nonprofits.show.about',
		'uses' => 'NonprofitsController@about'
	]);

	// forprofits
	Route::resource('businesses', 'ForprofitsController', [
		'only' => ['index', 'show', 'create', 'store'],
		'names' => [
			'index' => 'forprofits.index',
			'show' => 'forprofits.show',
			'create' => 'forprofits.create',
			'store' => 'forprofits.store',
		]
	]);

	Route::get('businesses/{business}/donations', [
		'as' => 'forprofits.show.donations',
		'uses' => 'ForprofitsController@donations'
	]);

	Route::get('businesses/{business}/about', [
		'as' => 'forprofits.show.about',
		'uses' => 'ForprofitsController@about'
	]);

	// volunteers
	Route::resource('volunteers', 'VolunteersController', ['only' => 'show']);

	Route::get('volunteers/{volunteer}/donations', [
		'as' => 'volunteers.show.donations',
		'uses' => 'VolunteersController@donations'
	]);

	// hours
	Route::get('hours', ['as' => 'hours.index', 'uses' => 'HoursController@index']);

	// incentives
	Route::get('incentives/{incentive}', ['as' => 'incentives.show', 'uses' => 'IncentivesController@show']);

	// incentives purchases
	Route::get('coupons', ['as' => 'incentive-purchases.index', 'uses' => 'IncentivePurchasesController@index']);
	Route::get('coupons/{incentive}.pdf', ['as' => 'incentive-purchases.show', 'uses' => 'IncentivePurchasesController@show']);

	// donations
	Route::get('donations/{donation}', [
		'as' => 'donations.show',
		'uses' => 'DonationsController@show',
	]);

	// invitations
	Route::get('invite-friends', [
		'as' => 'invite-friends',
		'uses' => 'InvitationsController@create'
	]);
	Route::post('invite-friends', 'InvitationsController@store');
});

// auth only
Route::group(['middleware' => ['auth'], 'namespace' => 'Volunteers'], function() {

	// settings
	Route::get('settings/account', ['as' => 'settings.account', 'uses' => 'SettingsController@account']);
	Route::put('settings/account', 'SettingsController@update_account');
	Route::get('settings/profile', ['as' => 'settings.profile', 'uses' => 'SettingsController@profile']);
	Route::put('settings/profile', 'SettingsController@update_profile');
	Route::put('settings/profile-image', ['as' => 'settings.profile-image-update', 'uses' => 'SettingsController@updateImage']);
	Route::get('settings/categories', ['as' => 'settings.categories', 'uses' => 'SettingsController@categories']);
	Route::put('settings/categories', 'SettingsController@update_categories');

	// settings billing
	Route::get('settings/billing', [
		'as' => 'settings.billing',
		'uses' => 'BillingController@edit'
	]);
	Route::get('settings/billing/receipts', [
		'as' => 'volunteers.settings.receipts',
		'uses' => 'BillingController@receipts'
	]);
	Route::get('settings/billing/receipts/{id}', [
		'as' => 'volunteers.settings.receipts.download',
		'uses' => 'BillingController@receipts_download'
	]);

	// welcome
	Route::get('welcome', ['as' => 'welcome', 'uses' => 'WelcomeController@get']);
	Route::put('welcome', 'WelcomeController@update');
});