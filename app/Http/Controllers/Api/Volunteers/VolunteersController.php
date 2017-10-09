<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;

use App\Http\Controllers\ApiController;
use App\Volunteer;
use App\Events\UserOnline;
use App\Events\UserOffline;

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
			$following = $volunteer->follows->where('user_id','=',$request->user()->id)->first();
			$this->items[] = [
				'volunteer' => $volunteer->toArray(),
				'following' => $following,
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

	// public function status(Request $request, $id) {
	// 	$volunteer = Volunteer::findOrFail($id);

	// 	$status = (bool)$request->status;

	// 	$volunteer->status = $status;
	// 	$volunteer->save();

	// 	if ($status) {
	// 		broadcast(new UserOnline($volunteer))->toOthers();
	// 	} else {
	// 		broadcast(new UserOffline($volunteer))->toOthers();
	// 	}
	// }
}
