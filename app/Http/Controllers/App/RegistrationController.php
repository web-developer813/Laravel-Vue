<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\RegisterUserRequest;
use DB;
use App\User;
use App\Volunteer;
use App\Mail\Volunteers\WelcomeEmail;
use Response;

use Mail;

class RegistrationController extends Controller
{
	# get
	public function get()
	{
		return view('app.auth.register');
	}

	# post
	public function post(RegisterUserRequest $request)
	{
		$user = DB::transaction(function() use ($request) {
			$user = User::create([
				'email' =>  $request->email,
				'password' =>  $request->password
			]);

			$user->volunteer()->save(new Volunteer([
				'name' =>  $request->name,
				'trial_ends_at' => Carbon::now()->addDays(7),
			]));

			return $user;
		});

        // clear sessions
        $request->session()->forget('auth-nonprofit');
        $request->session()->forget('auth-forprofit');

        // login user
		auth()->login($user);

		// invitations
		if (session()->has('invitation'))
			session()->get('invitation')->acceptedBy(auth()->id());

		// send welcome email
		Mail::send(new WelcomeEmail($user));
		
		// redirect to newsfeed
		return redirect()->route('newsfeed');
	}

	#check email
	public function checkemail(Request $request) 
	{
		$email = $request->get('email');
		$user = User::where('email',$email)->first();
		if($user) {
			return 'false';
		} 
		return 'true';
		
	}
}
