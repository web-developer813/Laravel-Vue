<?php
// app
Route::group(['namespace' => 'App'], function () {

    // auth
    Route::get('register', ['as' => 'register', 'uses' => 'RegistrationController@get']);
    Route::post('register', 'RegistrationController@post');
    // Route::post('checkemail', 'RegistrationController@checkemail');
    Route::get('checkemail', ['middleware' => 'cors', 'uses' => 'RegistrationController@checkemail']);

    Route::get('login', ['as' => 'login', 'uses' => 'AuthController@get']);
    Route::post('login', ['https' => true,'uses' => 'AuthController@post']);

    Route::get('forgot-password', ['as' => 'forgot-password', 'uses' => 'PasswordController@forgot_get']);
    Route::get('forgot-password/sent', ['as' => 'forgot-password-sent', 'uses' => 'PasswordController@forgot_sent']);
    Route::post('forgot-password', 'PasswordController@forgot_post');

    Route::get('reset-password', ['as' => 'reset-password', 'uses' => 'PasswordController@reset_get']);
    Route::post('reset-password', 'PasswordController@reset_post');

    Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

    // mode switcher
    Route::get('switch-mode/{mode}', ['as' => 'switch-mode', 'uses' => 'ModeController@switch_mode']);

    // invitations
    Route::get('invitations/{hash}', ['as' => 'invitations.accept', 'uses' => 'InvitationsController@accept']);

    // stripe webooks
    Route::post('stripe/webhook', 'StripeWebhookController@handleWebhook');

    // store stripe id
    Route::post('store-stripe-id', [
        'as' => 'api.save.stripe',
        'uses' => 'StripeWebhookController@storeStripeId'
    ]);

    // upload resource
    Route::resource('upload', 'UploadController');

    // Messages
    Route::get('messages', ['as' => 'app.messages.list', 'uses' => 'MessagesController@index']);
});
