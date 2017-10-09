<?php

namespace App\Http\Controllers\Api\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Nonprofits\StoreHoursRequest;
use App\Http\Controllers\Controller;
use DB;

class HoursController extends Controller
{
	protected $nonprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
	}

	# index
	public function index(Request $request, $nonprofit_id)
	{
		$input = (object) $request->only('opportunity');

		// query
		$query = $this->nonprofit->hours()->with('opportunity', 'volunteer');

		// for opportunity
		if ($request->opportunity)
			$query->whereOpportunityId($request->opportunity);

		// pagination
		$hours = $query->ordered()->paginate(20);
		$hours->appends($request->except('page'));

		// items
		$items = [];
		foreach($hours as $hour)
		{
			$items[] = [
				'hours' => $hour->toArray(),
				'volunteer' => $hour->volunteer->toArray(),
				'opportunity' => $hour->opportunity->toArray()
			];
		}

		return response()->json([
			'items' => $items,
			'nextPageUrl' => nextPageUrl($hours->nextPageUrl()),
		]);
	}

	# store
	public function store(StoreHoursRequest $request)
	{
		// data
		$opportunity = $this->nonprofit->opportunities()->where('opportunities.id', $request->opportunity)->firstOrFail();
		$volunteers = $opportunity->volunteers()->whereIn('volunteers.id', $request->volunteers)->get();
		$minutes = round(($request->hours * 60) + $request->minutes);
		$points = $minutes * 20 / 60;

		if (!$minutes)
			return response()->json(['Please enter hours.'], 400);
		
		// create hours for each volunteer
		foreach($volunteers as $volunteer)
		{
			DB::transaction(function() use ($volunteer, $request, $opportunity, $minutes, $points) {
				$volunteer->hours()->create([
					'opportunity_id' => $request->opportunity,
					'nonprofit_id' => $opportunity->nonprofit_id,
					'start_date' => $request->startDate,
					'end_date' => $request->endDate,
					'minutes' => $minutes,
					'points' => $points,
				]);
			});
		}

		return response()->json(['message' => 'Hours have been verified']);
	}

	# destroy
	public function destroy(Request $request, $nonprofit_id, $hours_id)
	{
		DB::transaction(function() use ($hours_id) {
			$hours = $this->nonprofit->hours()->findOrFail($hours_id);
			$hours->delete();
		});

		return response()->json(['message' => 'This entry has been deleted']);
	}
}
