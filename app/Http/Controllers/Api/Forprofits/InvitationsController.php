<?php

namespace App\Http\Controllers\Api\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;

class InvitationsController extends ApiController
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
		$query = $this->forprofit->invitations();

		// filter type
		if ($request->has('type'))
			$query->whereType($request->type);

		// filter status
		$query = $this->filterStatus($query, $request);
		$query = $this->search($query, $request);

		// return all ids
		$allIdsQuery = $query;
		$allInvitationsIds = $allIdsQuery->get()->pluck('id');

		// ordering
		$query->ordered();

		// pagination
		$invitations = $this->paginate($query, $request);

		// items
		foreach($invitations as $invitation)
		{
			$this->items[] = [
				'invitation' => $invitation->toArray(),
			];
		}

		return response()->json([
				'items' => $this->items,
				'allInvitationsIds' => $allInvitationsIds,
				'nextPageUrl' => nextPageUrl($invitations->nextPageUrl())
			]);
	}

	# filter status
	protected function filterStatus($query, $request)
	{
		switch($request->status)
		{
			case 'accepted':
				$query->accepted();
				break;

			case 'pending':
				$query->pending();
				break;
		}

		return $query;
	}

	# send
	public function send(Request $request, $forprofit_id, $invitation_id)
	{
		$invitation = $this->forprofit->invitations()->findOrFail($invitation_id);

		// send invitation
		$invitation->send();

		return response()->json(['message' => 'This invitation has been sent']);
	}

	# destroy
	public function destroy(Request $request, $forprofit_id, $invitation_id)
	{
		$invitation = $this->forprofit->invitations()->findOrFail($invitation_id);
		$invitation->delete();

		return response()->json(['message' => 'This invitation has been deleted']);
	}
}
