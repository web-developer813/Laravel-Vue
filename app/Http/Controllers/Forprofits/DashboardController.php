<?php

namespace App\Http\Controllers\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Forprofit;

class DashboardController extends Controller
{
	protected $forprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->forprofit = config()->get('authForprofit');
            return $next($request);
        });
	}

	# dashboard
	public function dashboard(Request $request, $forprofit_id)
	{
		return view('forprofits.dashboard.index');
	}
}
