<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Incentive;
use App\IncentivePurchase;
use DB;

class IncentivePurchasesController extends ApiController
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
		$query = $this->volunteer->incentivePurchases();
		
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
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($purchases->nextPageUrl())
			]);
	}

	# store
	public function store(Request $request, $incentive_id)
	{
		$incentive = Incentive::findOrFail($incentive_id);
		if($incentive->case != "" && $incentive->availablePurchase() == 0){
            return response()->json(['message' => "You can not purchase this incentive", 'emptyAvailable'  => 'emptyAvailable']);
        }
        if($incentive->case == 'flat') {
		    $quantity = $incentive->quantity;
            $incentive->quantity = $quantity- 1 ;
            $incentive->save();
        }
		$volunteer = auth()->user()->volunteer;

		// check that volunteer has enough points
		if ($volunteer->points < $incentive->price)
			return response()->json(['error_message' => 'You don\'t currently have enough points to buy this coupon']);

		$purchaseed = DB::transaction(function() use ($incentive, $volunteer) {


			// remove points from volunteer
			$volunteer->points = $volunteer->points - $incentive->price;
			$volunteer->save();



			// expires at
			$expires_at = ($incentive->days_to_use)
				? Carbon::now()->addDays($incentive->days_to_use)
				: null;

			// create coupon purchase
			$purchase = IncentivePurchase::create([
					'incentive_id' => $incentive->id,
					'volunteer_id' => $volunteer->id,
					'forprofit_id' => $incentive->forprofit_id,

					'title' => $incentive->title,
					'description' => $incentive->description,
					'summary' => $incentive->summary,
					'terms' => $incentive->terms,
					'image_id' => $incentive->image_id,
					'barcode' => $incentive->barcode,
					'coupon_code' => $incentive->coupon_code,
					'how_to_use' => $incentive->how_to_use,
					'price' => $incentive->price,
					'employee_specific' => $incentive->employee_specific,

					'expires_at' => $expires_at,
				]);

			return $purchase;
		});

		flash('Your coupon has been purchased!', 'success');
		return response()->json(['redirect_url' => route('incentive-purchases.index')]);
	}

	# send
	public function send(Request $request, $purchase_id)
	{
		$purchase = $this->volunteer->incentivePurchases()->findOrFail($purchase_id);

		// send by email code

		return response()->json(['message' => 'Your coupon has been sent to your email']);
	}
}
