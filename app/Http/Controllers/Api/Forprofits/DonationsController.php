<?php

namespace App\Http\Controllers\Api\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Donation;
use DB;

class DonationsController extends ApiController
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
	public function index(Request $request)
	{
		$query = $this->forprofit->donations();

		// status
		switch($request->status)
		{
			case 'fulfilled':
				$query->fulfilled();
				break;

			case 'pending':
				$query->pending();
				break;
		}

		// search
		if ($request->search)
			$query->search($request->search);

		// with relationships
		$query->with('nonprofit');

		// order
		$query->ordered();

		// pagination
		$donations = $query->paginate(20);
		$donations->appends($request->except('page'));

		// items
		$items = [];
		foreach($donations as $donation)
		{
			$items[] = [
				'donation' => $donation->toArray(),
				'nonprofit' => $donation->nonprofit->toArray()
			];
		}

		return response()->json([
			'items' => $items,
			'nextPageUrl' => nextPageUrl($donations->nextPageUrl()),
		]);
	}

	# update 
	public function update(Request $request, $forprofit_id, $donation_id)
	{
		$donation = $this->forprofit->donations()->findOrFail($donation_id);
		$donation->setFulfilled($request->fulfilled);
		return response()->json([
			'redirect_url' => route('forprofits.donations.edit', [$this->forprofit->id, $donation->id])
		]);
	}
}
