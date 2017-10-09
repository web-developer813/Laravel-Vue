<?php
// forprofits admin
Route::group(['middleware' => ['auth', 'forprofitsAdmin'], 'namespace' => 'Forprofits'], function() {
	
	Route::get('forprofits/{forprofit}/dashboard', [
		'as' => 'forprofits.dashboard', 'uses' => 'DashboardController@dashboard']);

	// settings
	Route::get('forprofits/{forprofit}/settings/contact', [
		'as' => 'forprofits.settings.contact', 'uses' => 'SettingsController@contact']);
	Route::put('forprofits/{forprofit}/settings/contact',
		'SettingsController@update_contact');
	Route::get('forprofits/{forprofit}/settings/profile', [
		'as' => 'forprofits.settings.profile', 'uses' => 'SettingsController@profile']);
	Route::put('forprofits/{forprofit}/settings/profile',
		'SettingsController@update_profile');
	Route::get('forprofits/{forprofit}/settings/categories', [
		'as' => 'forprofits.settings.categories', 'uses' => 'SettingsController@categories']);
	Route::put('forprofits/{forprofit}/settings/categories',
		'SettingsController@update_categories');

	// incentives
	Route::resource('forprofits.incentives', 'IncentivesController');
	Route::get('forprofits/{forprofit}/incentives/{incentive}/purchases', ['as' => 'forprofits.incentives.purchases', 'uses' => 'IncentivesController@purchases']);

	// donations
	Route::get('forprofits/{forprofit}/donations', [
		'as' => 'forprofits.donations.index',
		'uses' => 'DonationsController@index'
	]);
	Route::get('forprofits/{forprofit}/donations/{donation}', [
		'as' => 'forprofits.donations.edit',
		'uses' => 'DonationsController@edit'
	]);

	// employees
	Route::get('forprofits/{forprofit}/employees', ['as' => 'forprofits.employees.index', 'uses' => 'EmployeesController@index']);

	// invitations
	Route::get('forprofits/{forprofit}/employees/invitations', [
		'as' => 'forprofits.invitations.index',
		'uses' => 'InvitationsController@index'
	]);
	Route::get('forprofits/{forprofit}/employees/invite', [
		'as' => 'forprofits.invitations.create',
		'uses' => 'InvitationsController@create'
	]);
	Route::post('forprofits/{forprofit}/invitations/csv', [
		'as' => 'forprofits.invitations.store_csv',
		'uses' => 'InvitationsController@store_csv'
	]);
	Route::post('forprofits/{forprofit}/invitations/emails', [
		'as' => 'forprofits.invitations.store_emails',
		'uses' => 'InvitationsController@store_emails'
	]);

});