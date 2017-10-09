<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Opportunity;

class OpportunitiesController extends ApiController
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
		$query = Opportunity::published();

		// nonprofit profile
		if ($request->nonprofit)
			$query = $this->forNonprofitProfile($query, $request);
		
		// volunteer search
		else
			$query = $this->forVolunteerSearch($query, $request);

		// with relationships
		$query->with('nonprofit', 'categories');

		// order
		$query->orderedByCreationDate();

		// pagination
		$opportunities = $query->paginate(20);
		$opportunities->appends($request->except('page'));

		// items
		$items = [];
		foreach($opportunities as $opportunity)
		{
			$items[] = [
				'opportunity' => $opportunity->toArray(),
				'nonprofit' => $opportunity->nonprofit->toArray(),
				'authVolunteer' => ['has_applied' => $this->volunteer->hasAppliedForOpportunity($opportunity->id)],
			];
		}

		return response()->json([
			'items' => $items,
			'nextPageUrl' => nextPageUrl($opportunities->nextPageUrl()),
		]);
	}

	# for volunteer search
	protected function forVolunteerSearch($query, $request)
	{
		if ($request->search)
			$query->search($request->search);

		if ($request->has('categories'))
			$query->searchByCategories($request->categories);

		if ($request->has('virtual'))
			$query = ($request->virtual) ? $query->whereVirtual(1) : $query->whereVirtual(0);

		if ($request->has('flexible'))
			$query = ($request->flexible) ? $query->whereFlexible(1) : $query->whereFlexible(0);

		// not expired
		$query->notExpired();

		// near location or virtual
		$query->nearLatLng($this->volunteer->location_lat, $this->volunteer->location_lng, 100);

		return $query;
	}

	# for nonprofit profile
	protected function forNonprofitProfile($query, $request)
	{
		if ($request->nonprofit)
			$query->whereNonprofitId($request->nonprofit);

		// not expired
		$query->notExpired();
		
		return $query;
	}
}
