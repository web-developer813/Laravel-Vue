<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nonprofit;

class ManageController extends Controller
{
	protected $nonprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
	}

	# applications
	public function applications(Request $request, $nonprofit_id, $opportunity_id)
	{
		$opportunity = $this->nonprofit->opportunities()->whereId($opportunity_id)->firstOrFail();

		return view('nonprofits.manage.applications', compact('opportunity'));
	}

	# verify hours
	public function verify_hours(Request $request, $nonprofit_id, $opportunity_id)
	{
		$opportunity = $this->nonprofit->opportunities()->whereId($opportunity_id)->firstOrFail();
		$startDate = $opportunity->startDate ?: Carbon::now()->toDateString();
		$endDate = $opportunity->endDate ?: Carbon::now()->toDateString();

		return view('nonprofits.manage.verify-hours', compact('opportunity', 'startDate', 'endDate'));
	}

	# history
	public function history(Request $request, $nonprofit_id, $opportunity_id)
	{
		$opportunity = $this->nonprofit->opportunities()->whereId($opportunity_id)->firstOrFail();

		return view('nonprofits.manage.history', compact('opportunity'));
	}
}
