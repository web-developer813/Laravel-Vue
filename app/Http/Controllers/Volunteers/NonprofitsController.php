<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Volunteers\StoreNonprofitRequest;
use App\Http\Requests\Volunteers\UpdateNonProfitRequest;
use App\Helpers\Services\LocationService;
use App\Mail\Nonprofits\WelcomeEmail;
use App\Nonprofit;
use App\Role;
use App\Category;
use DB;

use Mail;

class NonprofitsController extends Controller
{
	# index
	public function index(Request $request)
	{
		return view('volunteers.nonprofits.index');
	}

	# show
	public function show(Request $request, $nonprofit_id)
	{
		$nonprofit = Nonprofit::verified()->find($nonprofit_id);

		// if employee of nonprofit
		if (!$nonprofit)
			$nonprofit = auth()->user()->nonprofits()->findOrFail($nonprofit_id);
		
		return view('volunteers.nonprofits.show', compact('nonprofit'));
	}

	# donations
	public function donations(Request $request, $nonprofit_id)
	{
		$nonprofit = Nonprofit::verified()->find($nonprofit_id);

		// if employee of nonprofit
		if (!$nonprofit)
			$nonprofit = auth()->user()->nonprofits()->findOrFail($nonprofit_id);

		return view('volunteers.nonprofits.donations', compact('nonprofit'));
	}

	# about
	public function about(Request $request, $nonprofit_id)
	{
		$nonprofit = Nonprofit::verified()->find($nonprofit_id);

		// if employee of nonprofit
		if (!$nonprofit)
			$nonprofit = auth()->user()->nonprofits()->findOrFail($nonprofit_id);

		return view('volunteers.nonprofits.about', compact('nonprofit'));
	}

	# create
	public function create()
	{
		$categories = Category::get();
		return view('volunteers.nonprofits.create', compact('categories'));
	}

	# store
	public function store(StoreNonprofitRequest $request)
	{
		$input = $request->only('name', 'description', 'mission', 'website_url', 'email', 'phone', 'location', 'location_suite', 'tax_id');
		$input = array_merge($input, LocationService::getFullAddress($request->location));
		$categories = $request->input('categories', []);

		// create nonprofit
		$nonprofit = DB::transaction(function() use ($input, $categories, $request) {
			$nonprofit = Nonprofit::create($input);
			$nonprofit->categories()->sync($categories);
			$nonprofit->updateProfilePhoto($request->file('photo'));
			$nonprofit->updateFile($request->file('file_501c3'), 'file_501c3');
			$nonprofit->startTrial();
			return $nonprofit;
		});

		// maker user the owner
		$role = Role::whereName('nonprofit_owner')->firstOrFail();
		$nonprofit->employees()->attach(config('authVolunteer')->id, [
			'user_id' => auth()->id(),
			'role_id' => $role->id
		]);

		// send welcome email
		Mail::send(new WelcomeEmail($nonprofit));

		// return to nonprofit
		flash('Your nonprofit organization has been created', 'success');
		return redirect()->route('switch-mode', ['mode' => 'nonprofit', 'nonprofit' => $nonprofit->id]);
	}
}
