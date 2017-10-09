<?php

namespace App\Http\Controllers\Api\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Forprofits\UpdateMonthlyPointsRequest;
use App\Http\Controllers\ApiController;

class SettingsController extends ApiController
{
	protected $forprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->forprofit = config()->get('authForprofit');
            return $next($request);
        });
	}

	# update monthly points
	public function update_monthly_points(UpdateMonthlyPointsRequest $request, $forprofit_id)
	{
		$this->forprofit->monthly_points = $request->monthly_points;
		$this->forprofit->save();
		
		return response()->json(['message' => 'Your monthly points allocation has been updated']);
	}
}
