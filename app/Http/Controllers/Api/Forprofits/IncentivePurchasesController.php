<?php

namespace App\Http\Controllers\Api\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;

class IncentivePurchasesController extends ApiController
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
	public function index(Request $request, $forprofit_id)
	{
		$query = $this->forprofit->incentivePurchases()->with('volunteer');
		
		// incentive
		if ($request->incentive)
			$query->whereIncentiveId($request->incentive);
		
		// search
		if ($request->search)
			$query->search($request->search);

		// status
		if ($request->status)
			switch($request->status)
			{
				case 'valid': $query->valid(); break;
				case 'redeemed': $query->redeemed(); break;
				case 'expired': $query->expired(); break;
			}

		// ordering
		$query->ordered();

		// pagination
		$purchases = $this->paginate($query, $request);

		// items
		foreach($purchases as $purchase)
		{
			$this->items[] = [
				'purchase' => $purchase->toArray(),
				'volunteer' => $purchase->volunteer->toArray(),
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($purchases->nextPageUrl())
			]);
	}

	# update
	public function update(Request $request, $forprofit_id, $purchase_id)
	{
		$purchase = $this->forprofit->incentivePurchases()->findOrFail($purchase_id);
		
		// if redeemed
		if ($request->has('redeemed'))
		{
			$purchase->setRedeemed($request->redeemed);
		}

		return response()->json([
			'message' => 'This coupon has been updated',
			'item' => [
				'purchase' => $purchase->toArray(),
				'volunteer' => $purchase->volunteer->toArray(),
			]
		]);
	}
}
