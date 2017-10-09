<?php
// nonprofits admin
Route::group(['middleware' => ['auth', 'nonprofitsAdmin'], 'namespace' => 'Nonprofits'], function() {

	Route::get('nonprofits/{nonprofit}/dashboard', [
		'as' => 'nonprofits.dashboard', 'uses' => 'DashboardController@dashboard']);

	// settings
	Route::get('nonprofits/{nonprofit}/settings/contact', [
		'as' => 'nonprofits.settings.contact', 'uses' => 'SettingsController@contact']);
	Route::put('nonprofits/{nonprofit}/settings/contact',
		'SettingsController@update_contact');
	Route::get('nonprofits/{nonprofit}/settings/profile', [
		'as' => 'nonprofits.settings.profile', 'uses' => 'SettingsController@profile']);
	Route::put('nonprofits/{nonprofit}/settings/profile',
		'SettingsController@update_profile');
	Route::get('nonprofits/{nonprofit}/settings/categories', [
		'as' => 'nonprofits.settings.categories', 'uses' => 'SettingsController@categories']);
	Route::put('nonprofits/{nonprofit}/settings/categories',
		'SettingsController@update_categories');

	// settings billing
	Route::get('nonprofits/{nonprofit}/settings/billing', [
		'as' => 'nonprofits.settings.billing',
		'uses' => 'BillingController@edit'
	]);
	Route::get('nonprofits/{nonprofit}/settings/billing/receipts', [
		'as' => 'nonprofits.settings.receipts',
		'uses' => 'BillingController@receipts'
	]);
	Route::get('nonprofits/{nonprofit}/settings/billing/receipts/{id}', [
		'as' => 'nonprofits.settings.receipts.download',
		'uses' => 'BillingController@receipts_download'
	]);

	// opportunities
	Route::resource('nonprofits.opportunities', 'OpportunitiesController');

	// manage opportunities
	Route::get('nonprofits/{nonprofit}/opportunities/{opportunity}/applications', 
		['as' => 'nonprofits.manage.applications', 'uses' => 'ManageController@applications']);
	Route::get('nonprofits/{nonprofit}/opportunities/{opportunity}/verify-hours', 
		['as' => 'nonprofits.manage.verify-hours', 'uses' => 'ManageController@verify_hours']);
	Route::get('nonprofits/{nonprofit}/opportunities/{opportunity}/history', 
		['as' => 'nonprofits.manage.history', 'uses' => 'ManageController@history']);

	// applications
	Route::resource('nonprofits.applications', 'ApplicationsController', ['only' => ['edit', 'update']]);

	// hours
	Route::get('nonprofits/{nonprofit}/hours', ['as' => 'nonprofits.hours.index', 'uses' => 'HoursController@index']);
	Route::get('nonprofits/{nonprofit}/hours/{hour}', ['as' => 'nonprofits.hours.edit', 'uses' => 'HoursController@edit']);

	// volunteers
	Route::get('nonprofits/{nonprofit}/volunteers', ['as' => 'nonprofits.volunteers.index', 'uses' => 'VolunteersController@index']);

	// volunteer invitations
	Route::get('nonprofits/{nonprofit}/volunteers/invitations', ['as' => 'nonprofits.volunteers.invitations.index', 'uses' => 'VolunteersController@invitations_index']);
	Route::get('nonprofits/{nonprofit}/volunteers/invite', ['as' => 'nonprofits.volunteers.invitations.create', 'uses' => 'VolunteersController@invitations_create']);

	// employees
	Route::get('nonprofits/{forprofit}/employees', ['as' => 'nonprofits.employees.index', 'uses' => 'EmployeesController@index']);

	// employee invitations
	Route::get('nonprofits/{nonprofit}/employees/invitations', ['as' => 'nonprofits.employees.invitations.index', 'uses' => 'EmployeesController@invitations_index']);
	Route::get('nonprofits/{nonprofit}/employees/invite', ['as' => 'nonprofits.employees.invitations.create', 'uses' => 'EmployeesController@invitations_create']);

	// store invitations
	Route::post('nonprofits/{nonprofit}/invitations/csv', ['as' => 'nonprofits.invitations.store_csv', 'uses' => 'InvitationsController@store_csv']);
	Route::post('nonprofits/{nonprofit}/invitations/emails', ['as' => 'nonprofits.invitations.store_emails', 'uses' => 'InvitationsController@store_emails']);

	// forprofits
	Route::get('nonprofits/{nonprofit}/businesses', ['as' => 'nonprofits.forprofits.index', 'uses' => 'ForprofitsController@index']);

	// donations
	Route::get('manage/nonprofits/{nonprofit}/donations', [
		'as' => 'nonprofits.donations.index',
		'uses' => 'DonationsController@index'
	]);
	
});