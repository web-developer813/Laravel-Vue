<?php
// volunteers
Route::group([
    'prefix' => 'api',
    'middleware' => ['auth'],
    'namespace' => 'Api\Volunteers'
    ], function () {

    // stripe api
    // Route::resources(['stripe',  'StripeWebhookController']);

    // get hours
    Route::get('hours', [
        'as' => 'api.volunteers.hours',
        'uses' => 'HoursController@index'
    ]);

    // hours only
    Route::get('hours/only', [
        'as' => 'api.volunteers.hours.only',
        'uses' => 'HoursController@hoursOnly'
    ]);
//
    // applications
    Route::get('applications', [
        'as' => 'api.volunteers.applications',
        'uses' => 'ApplicationsController@index'
    ]);

    // get incentives
    Route::get('incentives', [
        'as' => 'api.volunteers.incentives',
        'uses' => 'IncentivesController@index'
    ]);

    // purchase incentives
    Route::post('incentives/{incentive}/purchase', [
        'as' => 'api.volunteers.incentives-purchases.store',
        'uses' => 'IncentivePurchasesController@store'
    ]);

    // get purchased incentives
    Route::get('incentive-purchases', [
        'as' => 'api.volunteers.incentive-purchases',
        'uses' => 'IncentivePurchasesController@index'
    ]);

    // get purchased incentives
    Route::put('incentive-purchases/{purchase}/send', [
        'as' => 'api.volunteers.incentive-purchases.send',
        'uses' => 'IncentivePurchasesController@send'
    ]);

    // donations
    Route::get('donations', [
        'as' => 'api.volunteers.donations',
        'uses' => 'DonationsController@index'
    ]);

    

    // store donation
    Route::post('nonprofits/{nonprofit}/donate', [
        'as' => 'api.volunteers.donations.store',
        'uses' => 'DonationsController@store'
    ]);

    // get forprofits
    Route::get('volunteers/forprofits', [
        'as' => 'api.volunteers.forprofits',
        'uses' => 'ForprofitsController@index'
    ]);

    // get opportunities
    Route::get('opportunities', [
        'as' => 'api.volunteers.opportunities',
        'uses' => 'OpportunitiesController@index'
    ]);

    // get newsfeed
    Route::get('newsfeed', [
        'as' => 'api.volunteers.newsfeed',
        'uses' => 'NewsfeedController@index'
    ]);

    // get users
    Route::get('users', [
        'as' => 'api.volunteers.volunteers',
        'uses' => 'VolunteersController@index'
    ]);

        Route::put('users/{id}', [
        'as' => 'api.volunteers.status',
        'uses' => 'VolunteersController@status'
    ]);

    // get nonprofits
    Route::get('nonprofits', [
        'as' => 'api.volunteers.nonprofits',
        'uses' => 'NonprofitsController@index'
    ]);

    // store subscriptions
    Route::post('subscriptions', [
        'as' => 'api.volunteers.subscriptions.store',
        'uses' => 'SubscriptionsController@store'
    ]);

    // update subscriptions
    Route::put('subscriptions/update', [
        'as' => 'api.volunteers.subscriptions.update',
        'uses' => 'SubscriptionsController@update'
    ]);

    // cancel subscriptions
    Route::delete('subscriptions/cancel', [
        'as' => 'api.volunteers.subscriptions.destroy',
        'uses' => 'SubscriptionsController@destroy'
    ]);

    // update payment method
    Route::post('payment-method/update', [
        'as' => 'api.volunteers.update-credit-card',
        'uses' => 'PaymentMethodController@update'
    ]);

    // get skill
    Route::get('skill/{volunteer_id}', [
        'as' => 'api.volunteers.skills',
        'uses' => 'SkillsController@index'
    ]);

    // add skill
    Route::post('skill/{volunteer_id}/add', [
        'as' => 'api.volunteers.add-skill',
        'uses' => 'SkillsController@store'
    ]);


    // update skill
    Route::post('skill/{volunteer_id}/update', [
        'as' => 'api.volunteers.update-skill',
        'uses' => 'SkillsController@update'
    ]);

    // make endorsement
    Route::post('skill/{skill_id}/endorsement', [
        'as' => 'api.volunteers.add-endorsement',
        'uses' => 'SkillsController@addOrRemoveEndorsement'
    ]);

    // get list endorsers
    Route::get('skill/{skill_id}/endorsement/endorsers', [
        'as' => 'api.volunteers.add-endorsement',
        'uses' => 'SkillsController@getEndorsers'
    ]);
    });


// nonprofits
Route::group([
    'prefix' => 'api/nonprofits/{nonprofit}/admin',
    'middleware' => ['auth', 'nonprofitsAdmin'],
    'namespace' => 'Api\Nonprofits'
    ], function () {

    // opportunities
    Route::get('opportunities', [
        'as' => 'api.nonprofits.opportunities',
        'uses' => 'OpportunitiesController@index'
    ]);

    // get applications
    Route::get('applications', [
        'as' => 'api.nonprofits.applications',
        'uses' => 'ApplicationsController@index'
    ]);

    // get hours
    Route::get('hours', [
        'as' => 'api.nonprofits.hours',
        'uses' => 'HoursController@index'
    ]);

    // post hours
    Route::post('hours', [
        'as' => 'api.nonprofits.hours.store',
        'uses' => 'HoursController@store']);

    // delete hours
    Route::delete('hours/{hour}', [
        'as' => 'api.nonprofits.hours.destroy',
        'uses' => 'HoursController@destroy']);

    // post opportunities
    Route::post('opportunities', [
        'as' => 'api.nonprofits.opportunities.store',
        'uses' => 'OpportunitiesController@store']);

    // put opportunities
    Route::put('opportunities/{opportunity}', [
        'as' => 'api.nonprofits.opportunities.update',
        'uses' => 'OpportunitiesController@update']);

    // get forprofits
    Route::get('nonprofits/forprofits', [
        'as' => 'api.nonprofits.forprofits',
        'uses' => 'ForprofitsController@index'
    ]);

    // store donation
    Route::post('forprofits/{forprofit}/request-donation', [
        'as' => 'api.nonprofits.donations.store',
        'uses' => 'DonationsController@store'
    ]);

    // get donations
    Route::get('donations', [
        'as' => 'api.nonprofits.donations',
        'uses' => 'DonationsController@index'
    ]);

    // get invitations
    Route::get('invitations', [
        'as' => 'api.nonprofits.invitations',
        'uses' => 'InvitationsController@index'
    ]);

    // send invitation
    Route::put('invitations/{invitation}', [
        'as' => 'api.nonprofits.invitations.send',
        'uses' => 'InvitationsController@send'
    ]);

    // delete invitation
    Route::delete('invitations/{invitation}', [
        'as' => 'api.nonprofits.invitations.delete',
        'uses' => 'InvitationsController@destroy'
    ]);

    // employees
    Route::get('employees', [
        'as' => 'api.nonprofits.employees',
        'uses' => 'EmployeesController@index'
    ]);

    // update employee
    Route::put('employees/{employee}', [
        'as' => 'api.nonprofits.employees.update',
        'uses' => 'EmployeesController@update'
    ]);

    // delete employee
    Route::delete('employees/{employee}', [
        'as' => 'api.nonprofits.employees.delete',
        'uses' => 'EmployeesController@destroy'
    ]);

    // volunteers
    Route::get('volunteers', [
        'as' => 'api.nonprofits.volunteers',
        'uses' => 'VolunteersController@index'
    ]);

    // get skill opportunity
    Route::get('opportunities/{opportunity_id}/skills', [
        'as' => 'api.nonprofits.opportunities.skills',
        'uses' => 'OpportunitySkillController@index'
    ]);

    // add skill opportunity
    Route::post('opportunities/{opportunity_id}/skills', [
        'as' => 'api.nonprofits.opportunities.skills.add',
        'uses' => 'OpportunitySkillController@store'
    ]);

    // update skill opportunity
    Route::post('opportunities/{opportunity_id}/skills/update', [
        'as' => 'api.nonprofits.opportunities.skills.add',
        'uses' => 'OpportunitySkillController@update'
    ]);
    });

// forprofits
Route::group([
    'prefix' => 'api/forprofits/{forprofit}/admin',
    'middleware' => ['auth', 'forprofitsAdmin'],
    'namespace' => 'Api\Forprofits'
    ], function () {

    // employees
    Route::get('employees', [
        'as' => 'api.forprofits.employees',
        'uses' => 'EmployeesController@index'
    ]);

    // update employee
    Route::put('employees/{employee}', [
        'as' => 'api.forprofits.employees.update',
        'uses' => 'EmployeesController@update'
    ]);

    // delete employee
    Route::delete('employees/{employee}', [
        'as' => 'api.forprofits.employees.delete',
        'uses' => 'EmployeesController@destroy'
    ]);

    // get invitations
    Route::get('invitations', [
        'as' => 'api.forprofits.invitations',
        'uses' => 'InvitationsController@index'
    ]);

    // send invitation
    Route::put('invitations/{invitation}', [
        'as' => 'api.forprofits.invitations.send',
        'uses' => 'InvitationsController@send'
    ]);

    // delete invitation
    Route::delete('invitations/{invitation}', [
        'as' => 'api.forprofits.invitations.delete',
        'uses' => 'InvitationsController@destroy'
    ]);

    // incentives
    Route::resource('incentives', 'IncentivesController', [
        'only' => ['index', 'store', 'update', 'destroy'],
        'names' => [
            'index' => 'api.forprofits.incentives',
            'store' => 'api.forprofits.incentives.store',
            'update' => 'api.forprofits.incentives.update',
            'destroy' => 'api.forprofits.incentives.delete',
        ]
    ]);

    // get purchased incentives
    Route::get('incentive-purchases', [
        'as' => 'api.forprofits.incentive-purchases',
        'uses' => 'IncentivePurchasesController@index'
    ]);

    // update purchased incentive status
    Route::put('incentive-purchases/{incentive_purchase}', [
        'as' => 'api.forprofits.incentive-purchases.update',
        'uses' => 'IncentivePurchasesController@update'
    ]);

    // update monthly points
    Route::put('settings/monthly-points', [
        'as' => 'api.forprofits.settings.monthly-points',
        'uses' => 'SettingsController@update_monthly_points'
    ]);

    // get donations
    Route::get('donations', [
        'as' => 'api.forprofits.donations',
        'uses' => 'DonationsController@index'
    ]);

    // update donation
    Route::put('donations/{donation}', [
        'as' => 'api.forprofits.donations.update',
        'uses' => 'DonationsController@update'
    ]);

    // get hours
    Route::get('hours', [
        'as' => 'api.forprofits.hours',
        'uses' => 'HoursController@index'
    ]);
    });

// api
Route::group([
    'prefix' => 'api/admin',
    'middleware' => ['auth', 'admin'],
    'namespace' => 'Api\Admin'
    ], function () {
        Route::get('volunteers', [
        'as' => 'api.admin.volunteers',
        'uses' => 'VolunteersController@index'
    ]);

        Route::put('volunteers/{volunteer}/upgrade-to-free', [
        'as' => 'api.admin.volunteers.upgrade-to-free',
        'uses' => 'VolunteersController@upgrade_to_free'
    ]);
    });

// volunteers
Route::group([
    'prefix' => 'api',
    'middleware' => ['auth'],
    'namespace' => 'Api\Social'
    ], function () {

    // Social Post
    Route::resource('posts', 'PostsController', ['except' => ['create','edit']]);

    // Likes
    Route::resource('likes', 'LikesController', ['except' => ['create','update','edit','show']]);

    // Follows
    Route::resource('follows', 'FollowsController', ['except' => ['create','update','edit','show']]);

    // Connections
    Route::post('connections/list', [
        'as' => 'connections.index',
        'uses' => 'ConnectionsController@index'
    ]);

        Route::post('connections', [
        'as' => 'connections.store',
        'uses' => 'ConnectionsController@store'
    ]);

        Route::get('twilio/token', [
        'as' => 'twilio.token',
        'uses' => 'TwilioController@generateToken'
    ]);

        Route::post('twilio/openChannel', [
        'as' => 'twilio.channel.open',
        'uses' => 'TwilioController@openChannel'
    ]);

    // Messages
    Route::get('threads/{id}', ['as' => 'messages.list', 'uses' => 'ThreadsController@getMessages']);

    Route::get('threads', ['as' => 'threads.list', 'uses' => 'ThreadsController@index']);

    Route::post('threads', ['as' => 'thread.create', 'uses' => 'ThreadsController@store']);

    Route::post('threads/read', ['as' => 'thread.read', 'uses' => 'ThreadsController@markRead']);

    Route::put('threads', ['as' => 'thread.update', 'uses' => 'ThreadsController@update']);

    // Sandbox
    Route::get('notifications', ['as' => 'notifications','uses' => 'NotificationsController@getIndex']);
        Route::post('notifications', ['as' => 'notifications','uses' => 'NotificationsController@postNotify']);
    });
