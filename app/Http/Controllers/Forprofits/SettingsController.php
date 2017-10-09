<?php

namespace App\Http\Controllers\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Forprofits\UpdateProfileSettingsRequest;
use App\Http\Requests\Forprofits\UpdateContactSettingsRequest;
use App\Http\Controllers\Controller;
use App\Helpers\Services\LocationService;
use App\Forprofit;
use DB;

class SettingsController extends Controller
{
	protected $forprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->forprofit = config()->get('authForprofit');
            return $next($request);
        });
	}

	# profile
	public function profile()
	{
		return view('forprofits.settings.profile');
	}

	# update profile
	public function update_profile(UpdateProfileSettingsRequest $request)
	{
		$input = $request->only(['name', 'description', 'mission', 'website_url']);

		DB::transaction(function()  use ($input, $request) {
			$this->forprofit->update($input);
			$this->forprofit->updateProfilePhoto($request->file('photo'));
		});
		
		flash('Your settings have been updated', 'success');
		return redirect()->route('forprofits.settings.profile', $this->forprofit->id);
	}

	# contact
	public function contact()
	{
		return view('forprofits.settings.contact');
	}

	# update contact
	public function update_contact(UpdateContactSettingsRequest $request)
	{
		$input = $request->only(['email', 'phone', 'location', 'location_suite']);
		$input = array_merge($input, LocationService::getFullAddress($request->location, $this->forprofit->location));

		DB::transaction(function()  use ($input) {
			$this->forprofit->update($input);
		});
		
		flash('Your settings have been updated', 'success');
		return redirect()->route('forprofits.settings.contact', $this->forprofit->id);
	}
}
