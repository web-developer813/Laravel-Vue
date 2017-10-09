<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\ApiController;
use App\Volunteer;

class VolunteersController extends ApiController
{
	# index
	public function index(Request $request)
	{
		$query = Volunteer::query();
		
		// search
		if ($request->search)
			$query->search($request->search);

		// ordering
		$query->ordered();

		// relationships
		$query->with('user');

		// pagination
		$total_volunteers = $this->count($query);
		$volunteers = $this->paginate($query, $request);

		// items
		foreach($volunteers as $volunteer)
		{
			$this->items[] = [
				'volunteer' => $volunteer->toArray(),
				'admin' => [
					'upgrade_to_free_url' => route('api.admin.volunteers.upgrade-to-free', $volunteer->id),
					'plan_id' => $volunteer->subscribed('main') ? $volunteer->subscription('main')->stripe_plan : 'basic',
					'email' => $volunteer->email
				]
			];
		}

		return response()->json([
				'items' => $this->items,
				'meta' => [
					'count' => $total_volunteers
				],
				'nextPageUrl' => nextPageUrl($volunteers->nextPageUrl())
			]);
	}

	# upgrade to free
	public function upgrade_to_free(Request $request, $volunteer_id)
	{
		$volunteer = Volunteer::findOrFail($volunteer_id);

		// if already subscribed
		if ($volunteer->subscribed('main'))
			$volunteer->subscription('main')->swap('free');
		else
			$volunteer->newSubscription('main', 'free')->create(null);

		// fresh not working, load subscriptions instead
		$volunteer->load('subscriptions');

		return response()->json([
			'message' => $volunteer->name . ' has been upgraded to the free plan',
			'item' => [
				'volunteer' => $volunteer->toArray(),
				'admin' => [
					'upgrade_to_free_url' => route('api.admin.volunteers.upgrade-to-free', $volunteer->id),
					'plan_id' => $volunteer->subscribed('main') ? $volunteer->subscription('main')->stripe_plan : 'basic'
				]
			]
		]);
	}
}
