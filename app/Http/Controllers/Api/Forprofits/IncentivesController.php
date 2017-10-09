<?php

namespace App\Http\Controllers\Api\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Forprofits\StoreIncentiveRequest;
use App\Http\Requests\Forprofits\UpdateIncentiveRequest;
use App\Http\Controllers\ApiController;
use App\Incentive;
use DB;

class IncentivesController extends ApiController
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
		$query = $this->forprofit->incentives();

		// search
		if($request->search)
			$query->search($request->search);

		// filter status
		// $query = $this->filterStatus($query, $request);
		// $query = $this->search($query, $request);

		// ordering
		$query->ordered();

		// pagination
		$incentives = $this->paginate($query, $request);

		// items
		foreach($incentives as $incentive)
		{
			$this->items[] = [
				'incentive' => $incentive->toArray(),
				'admin' => [
					'edit_url' => $incentive->edit_url,
					'purchases_url' => $incentive->purchases_url,
					'purchases_count' => $incentive->purchases()->count()
				]
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($incentives->nextPageUrl())
			]);
	}

	# store
	public function store(StoreIncentiveRequest $request, $forprofit)
	{
		$input = $request->only(
			'title', 'description', 'summary',
			'coupon_code', 'days_to_use', 'how_to_use', 'terms',
			'price', 'employee_specific','case','tag');
		if($request->input('case') == 'unlimit'){
		    $input['quantity'] = "";
        }else{
            $input['quantity'] = $request->input('quantity');
        }
		$input['forprofit_id'] = $this->forprofit->id;

		// create incentive
		$incentive = DB::transaction(function() use ($input, $request) {
			$incentive = Incentive::create(array_filter($input));
			$incentive->updateImage($request->file('photo'));
			$incentive->updateFile($request->file('barcode'), 'barcode');

			$incentive->setActive($request->active);
			return $incentive;
		});

		// return redirect url
		flash('This incentive has been created', 'success');

		return response()->json(['redirect_url' => route('forprofits.incentives.index', $this->forprofit->id)]);
	}

	# update
	public function update(UpdateIncentiveRequest $request, $forprofit_id, $incentive_id)
	{
		// incentive
		$incentive = $this->forprofit->incentives()->findOrFail($incentive_id);

		// input
		$input = $request->only(
			'title', 'description', 'summary',
			'coupon_code', 'days_to_use', 'how_to_use', 'terms',
			'price', 'quantity', 'employee_specific','case','tag');

        if($request->input('case') == 'unlimit'){
            $input['quantity'] = "";
        }else{
            $input['quantity'] = $request->input('quantity');
        }
		// update incentive
		DB::transaction(function() use ($incentive, $input, $request) {
			$incentive->update($input);
			$incentive->updateImage($request->file('photo'));
			$incentive->updateFile($request->file('barcode'), 'barcode');
			$incentive->setActive($request->active);
		});

		return response()->json(['message' => 'Your changes have been saved']);
	}
}