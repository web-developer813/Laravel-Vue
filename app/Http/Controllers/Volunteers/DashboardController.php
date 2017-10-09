<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Volunteer;
use Auth;
	
class DashboardController extends Controller
{
	protected $volunteer;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->volunteer = config()->get('authVolunteer');
            return $next($request);
        });
	}

	# dashboard
	public function dashboard(Request $request)
	{
		return view('volunteers.dashboard.index');
	}
}
