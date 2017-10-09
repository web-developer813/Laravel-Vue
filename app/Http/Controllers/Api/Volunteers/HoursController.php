<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Volunteer;

class HoursController extends Controller
{
	protected $volunteer;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->volunteer = auth()->user()->volunteer;
            return $next($request);
        });
	}
	
	# index
	public function index(Request $request)
	{
		// volunteer
		$volunteer = ($request->volunteer)
			? Volunteer::findOrFail($request->volunteer)
			: $this->volunteer;

		// query
		$query = $volunteer->hours()->with('opportunity', 'opportunity.categories', 'nonprofit');

		// order
		$query->ordered();

		// pagination
		$hours = $query->paginate(20);
		$hours->appends($request->except('page'));

		// items
		$items = [];
		foreach($hours as $hour)
		{
			$items[] = [
				'hours' => $hour->toArray(),
				'nonprofit' => $hour->nonprofit->toArray(),
				'opportunity' => $hour->opportunity->toArray()
			];
		}

		return response()->json([
			'items' => $items,
			'nextPageUrl' => nextPageUrl($hours->nextPageUrl()),
		]);
	}

	# get hours only
	public function hoursOnly(Request $request) {
		$volunteer = ($request->volunteer) ? Volunteer::findOrFail($request->volunteer) : $this->volunteer;

		$query = $volunteer->hours();
		$query->ordered();

		$hours = $query->paginate(10);

		$hours = [];
		$points = [];

		foreach($hours as $hour) {
			$hours[] = floor($hour->minutes / 60);
			$points[] = $hour->points;
		}

		return response()->json(array($hours,$points));
	}
}
