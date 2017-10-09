<?php

namespace App\Http\Controllers\Api\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Application;
use App\Nonprofit;

class ApplicationsController extends Controller
{
	protected $nonprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
	}

	# index
	public function index(Request $request, $nonprofit_id)
	{
		$query = $this->nonprofit->applications();

		// for opportunity
		if ($opportunity_id = $request->get('opportunity'))
			$query = $query->whereOpportunityId($opportunity_id);

		// status
		switch($request->status)
		{
			case 'accepted':
				$query->accepted();
				break;

			case 'rejected':
				$query->rejected();
				break;

			case 'pending':
				$query->pending();
				break;
		}

		// search
		if ($request->search)
			$query->search($request->search);

		// query
		$allIdsQuery = $query;
		$allVolunteersIds = $allIdsQuery->get()->pluck('volunteer.id');

		// relationships
		$query->with('opportunity', 'volunteer');

		// pagination
		$applications = $query->ordered()->paginate(20);
		$applications->appends($request->except('page'));

		// items
		$items = [];
		foreach($applications as $application)
		{
			$volunteer = $application->volunteer;
			$items[] = [
				'application' => $application->toArray(),
				'volunteer' => $volunteer->toArray(),
				'opportunity' => $application->opportunity->toArray(),
				'admin' => [
					'edit_url' => route('nonprofits.applications.edit', [$this->nonprofit->id, $application->id]),
					'minutes_count' => $volunteer->hours()->whereOpportunityId($application->opportunity_id)->sum('minutes')
				]
			];
		}

		return response()->json([
				'items' => $items,
				'allVolunteerIds' => $allVolunteersIds,
				'nextPageUrl' => nextPageUrl($applications->nextPageUrl())
			]);
	}
}
