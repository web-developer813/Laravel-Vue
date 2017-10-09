<?php

namespace App\Http\Controllers\Api\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;

use App\Hours;

class HoursController extends ApiController
{
	protected $forprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->forprofit = config()->get('authForprofit');
            return $next($request);
        });
	}

	# index
	public function index(Request $request, $forprofit)
	{
		// get all employees ids
		$ids = $this->forprofit->employees()->pluck('volunteers.id');

		// query
		$query = Hours::whereIn('volunteer_id', $ids);

		// order
		$query->ordered();

		// relationsips
		$query->with('opportunity', 'opportunity.categories', 'nonprofit', 'volunteer');

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
				'opportunity' => $hour->opportunity->toArray(),
				'volunteer' => $hour->volunteer->toArray()
			];
		}

		return response()->json([
			'items' => $items,
			'nextPageUrl' => nextPageUrl($hours->nextPageUrl()),
		]);
	}
}
