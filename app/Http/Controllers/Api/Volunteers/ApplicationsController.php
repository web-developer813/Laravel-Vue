<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Application;

class ApplicationsController extends ApiController
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
		$query = $this->volunteer->applications();

		// search
		if ($request->search)
			$query->search($request->search);

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

		// with relationships
		$query->with('opportunity', 'opportunity.nonprofit');

		// order
		$query->ordered();

		// pagination
		$applications = $query->paginate(20);
		$applications->appends($request->except('page'));

		// items
		$items = [];
		foreach($applications as $application)
		{
			$items[] = [
				'application' => $application->toArray(),
				'opportunity' => $application->opportunity->toArray(),
				'nonprofit' => $application->nonprofit->toArray()
			];
		}

		return response()->json([
			'items' => $items,
			'nextPageUrl' => nextPageUrl($applications->nextPageUrl()),
		]);
	}
}
