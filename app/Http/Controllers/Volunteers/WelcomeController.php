<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Volunteers\UpdateWelcomeRequest;
use App\Helpers\Services\LocationService;
use App\Jobs\GeocodeLocation;
use DB;

class WelcomeController extends Controller
{
	# get
	public function get()
	{
		return view('app.auth.welcome');
	}

	# update
	public function update(UpdateWelcomeRequest $request)
	{
		$volunteer = auth()->user()->volunteer;

		$input = $request->only('location');
		$input = array_merge($input, LocationService::getCoordinates($request->location, $volunteer->location));

		$categories = $request->input('categories', []);

		// Follow Feeds 
		$feed = \FeedManager::getUserFeed($volunteer->id);
		if (empty($feed)) throw new CustomValidationException(['categories' => 'There was an error finding your user feed. Please try again later.']);
		
		$cats = [];
		foreach ($categories as $category) {
			$followed = $feed->followFeed('categories', $category);
			if ($followed) $cats[] = $category;
		}

		DB::transaction(function() use ($volunteer, $input, $cats) {
			$volunteer->update($input);
			$volunteer->categories()->sync($cats);
		});

		// redirect to newsfeed
		return redirect()->route('dashboard');
	}
}
