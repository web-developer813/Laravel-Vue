<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Forprofit;

class ForprofitsController extends ApiController
{
	# index
	public function index(Request $request)
	{
		$query = Forprofit::verified();

		// search
		if ($request->search)
			$query->search($request->search);

		// ordering
		$query->ordered();

		// pagination
		$forprofits = $this->paginate($query, $request);

		// items
		foreach($forprofits as $forprofit)
		{
			$this->items[] = [
				'forprofit' => $forprofit->toArray(),
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($forprofits->nextPageUrl())
			]); 
	}
}
