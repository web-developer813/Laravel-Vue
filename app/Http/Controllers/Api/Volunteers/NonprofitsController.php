<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Nonprofit;

class NonprofitsController extends ApiController
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
		$query = Nonprofit::verified();

		if ($request->categories)
			$query->searchByCategories($request->categories);

		// search
		if ($request->search)
			$query->search($request->search);

		// pluck ids
		$nonprofits_ids = $query->pluck('id');
		
		// then order by distance
		$query = Nonprofit::with('categories')
			->withDistance($this->volunteer->location_lat, $this->volunteer->location_lng, 3000)
			->whereIn('id', $nonprofits_ids)
			->orderedByDistance();

		// pagination
		$nonprofits = $this->paginate($query, $request);

		// items
		foreach($nonprofits as $nonprofit)
		{
			$following = $nonprofit->follows->where('user_id','=',$this->volunteer->id)->first();
			$this->items[] = [
				'nonprofit' => $nonprofit->toArray(),
				'following' => $following
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($nonprofits->nextPageUrl())
			]);
	}
}
