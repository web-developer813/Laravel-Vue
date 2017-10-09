<?php

namespace App\Http\Controllers\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Forprofit;

class IncentivesController extends Controller
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
		return view('forprofits.incentives.index');
	}

	# create
	public function create(Request $request)
	{
		return view('forprofits.incentives.create');
	}

	# edit
	public function edit(Request $request, $forprofit_id, $incentive_id)
	{
		$incentive = $this->forprofit->incentives()->findOrFail($incentive_id);

		return view('forprofits.incentives.edit', compact('incentive'));
	}

	# purchases
	public function purchases(Request $request, $forprofit_id, $incentive_id)
	{
		$incentive = $this->forprofit->incentives()->findOrFail($incentive_id);
		
		return view('forprofits.incentives.purchases', compact('incentive'));
	}
}