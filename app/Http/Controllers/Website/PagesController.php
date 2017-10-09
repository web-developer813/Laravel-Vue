<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nonprofit;

class PagesController extends Controller
{
	# home
	public function home() {
		if (auth()->check())
		{
			session()->reflash();

			// check if nonprofit
			if (session()->has('auth-nonprofit'))
				return redirect()->route('nonprofits.dashboard', session()->get('auth-nonprofit')->id);

			// check if forprofit
			if (session()->has('auth-forprofit'))
				return redirect()->route('forprofits.dashboard', session()->get('auth-forprofit')->id);

			return redirect()->route('dashboard');
		}
		
		$nonprofits = Nonprofit::verified()->inRandomOrder()->whereNotNull('profile_photo_id')->limit(5)->get();
		return view('app.auth.login', compact('nonprofits'));
	}
}
