<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Incentive;
use App\IncentivePurchase;
use DB;

class IncentivesController extends ApiController
{
	# index
	public function index(Request $request)
	{
		$query = Incentive::available();

		// for forprofit profile
		if ($request->forprofit)
			$query = $this->forForprofitProfile($query, $request);

		// ordering
		$query->ordered();

		// pagination
		$incentives = $this->paginate($query, $request);

		// items
		foreach($incentives as $incentive)
		{
			$this->items[] = [
				'incentive' => $incentive->toArray(),
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($incentives->nextPageUrl())
			]);
	}

	# for forprofit prifile
	protected function forForprofitProfile($query, $request)
	{
		return $query->whereForprofitId($request->forprofit);
	}
}
