<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Nonprofits\UpdateProfileSettingsRequest;
use App\Http\Requests\Nonprofits\UpdateCategoriesSettingsRequest;
use App\Http\Requests\Nonprofits\UpdateContactSettingsRequest;
use App\Http\Controllers\Controller;
use App\Helpers\Services\LocationService;
use App\Nonprofit;
use App\Category;
use DB;

class SettingsController extends Controller
{
	protected $nonprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
	}

	# profile
	public function profile()
	{
		return view('nonprofits.settings.profile');
	}

	# update profile
	public function update_profile(UpdateProfileSettingsRequest $request)
	{
		$input = $request->only(['name', 'description', 'mission', 'website_url']);

		DB::transaction(function()  use ($input, $request) {
			$this->nonprofit->update($input);
			$this->nonprofit->updateProfilePhoto($request->file('photo'));
		});
		
		flash('Your settings have been updated', 'success');
		return redirect()->route('nonprofits.settings.profile', $this->nonprofit->id);
	}
	
	# categories
	public function categories()
	{
		$categories = Category::get();
		return view('nonprofits.settings.categories', compact('categories'));
	}

	# update categories
	public function update_categories(UpdateCategoriesSettingsRequest $request)
	{
		$categories = $request->input('categories', []);

		DB::transaction(function() use ($categories) {
			$this->nonprofit->categories()->sync($categories);
		});

		flash('Your settings have been updated', 'success');
		return redirect()->route('nonprofits.settings.categories', $this->nonprofit->id);
	}

	# contact
	public function contact()
	{
		return view('nonprofits.settings.contact');
	}

	# update contact
	public function update_contact(UpdateContactSettingsRequest $request)
	{
		$input = $request->only(['email', 'phone', 'location', 'location_suite', 'tax_id']);
		$input = array_merge($input, LocationService::getFullAddress($request->location, $this->nonprofit->location));

		DB::transaction(function()  use ($input, $request) {
			$this->nonprofit->update($input);
			$this->nonprofit->updateFile($request->file('file_501c3'), 'file_501c3');
		});
		
		flash('Your settings have been updated', 'success');
		return redirect()->route('nonprofits.settings.contact', $this->nonprofit->id);
	}
}
