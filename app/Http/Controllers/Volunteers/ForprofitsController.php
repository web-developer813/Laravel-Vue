<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Volunteers\StoreForprofitRequest;
use App\Http\Controllers\Controller;
use App\Helpers\Services\LocationService;
use App\Mail\Forprofits\WelcomeEmail;
use App\Forprofit;
use App\Role;
use DB;

use Mail;

class ForprofitsController extends Controller
{
	# index
	public function index()
	{
		return view('volunteers.forprofits.index');
	}

	# show
	public function show(Request $request, $forprofit_id)
	{
		$forprofit = Forprofit::whereVerified(1)->find($forprofit_id);

		// if employee of nonprofit
		if (!$forprofit)
			$forprofit = auth()->user()->forprofits()->findOrFail($forprofit_id);
		
		return view('volunteers.forprofits.show', compact('forprofit'));
	}

	# donations
	public function donations(Request $request, $forprofit_id)
	{
		$forprofit = Forprofit::whereVerified(1)->find($forprofit_id);

		// if employee of nonprofit
		if (!$forprofit)
			$forprofit = auth()->user()->forprofits()->findOrFail($forprofit_id);

		return view('volunteers.forprofits.donations', compact('forprofit'));
	}

	# about
	public function about(Request $request, $forprofit_id)
	{
		$forprofit = Forprofit::whereVerified(1)->find($forprofit_id);

		// if employee of nonprofit
		if (!$forprofit)
			$forprofit = auth()->user()->forprofits()->findOrFail($forprofit_id);
		
		return view('volunteers.forprofits.about', compact('forprofit'));
	}

	# create
	public function create()
	{
		return view('volunteers.forprofits.create');
	}

	# store
	public function store(StoreForprofitRequest $request)
	{
		$input = $request->only('name', 'description', 'mission', 'website_url', 'email', 'phone', 'location', 'location_suite');
		$input = array_merge($input, LocationService::getFullAddress($request->location));
		
		// create forprofit
		$forprofit = DB::transaction(function() use ($input, $request) {
			$forprofit = Forprofit::create($input);
			$forprofit->updateProfilePhoto($request->file('photo'));
			$forprofit->startTrial();
			return $forprofit;
		});

		// maker user the owner
		$role = Role::whereName('forprofit_owner')->firstOrFail();
		$forprofit->employees()->attach(config('authVolunteer')->id, [
			'user_id' => auth()->id(),
			'role_id' => $role->id
		]);

		// send welcome email
		Mail::send(new WelcomeEmail($forprofit));

		// return to forprofit
		return redirect()->route('switch-mode', ['mode' => 'forprofit', 'forprofit' => $forprofit->id]);
	}
}