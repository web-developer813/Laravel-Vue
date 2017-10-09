<?php

namespace App\Http\Controllers\Api\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Role;

class EmployeesController extends ApiController
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
		$query = $this->nonprofit->employees();
		
		// search
		if ($request->search)
			$query->search($request->search);

		// ordering
		$query->ordered();

		// pagination
		$volunteers = $this->paginate($query, $request);

		// items
		foreach($volunteers as $volunteer)
		{
			$this->items[] = [
				'volunteer' => $volunteer->toArray(),
				'admin' => [
					'update_url' => route('api.nonprofits.employees.update', [$this->nonprofit->id, $volunteer->id]),
					'delete_url' => route('api.nonprofits.employees.delete', [$this->nonprofit->id, $volunteer->id]),
				],
				'role' => $volunteer->nonprofitRole($this->nonprofit->id)->toArray(),
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($volunteers->nextPageUrl())
			]);
	}

	# update
	public function update(Request $request, $nonprofit_id, $volunteer_id)
	{
		$role = Role::whereName($request->role)->firstOrFail();
		$volunteer = $this->nonprofit->employees()->findOrFail($volunteer_id);
		$current_role =  $volunteer->nonprofitRole($this->nonprofit->id);

		// owner
		if ($current_role->name == 'nonprofit_owner')
			return response()->json(['message' => 'You are not authorized to perform this action'], 403);

		$this->nonprofit->employees()->detach($volunteer->id);
		$this->nonprofit->employees()->attach($volunteer->id, [
			'user_id' => $volunteer->user_id,
			'role_id' => $role->id
		]);

		return response()->json([
			'message' => $volunteer->name . ' has been updated',
			'item' => [
				'volunteer' => $volunteer->toArray(),
				'admin' => [
					'update_url' => route('api.nonprofits.employees.update', [$this->nonprofit->id, $volunteer->id]),
					'delete_url' => route('api.nonprofits.employees.delete', [$this->nonprofit->id, $volunteer->id]),
				],
				'role' => $volunteer->nonprofitRole($this->nonprofit->id)->toArray(),
			]
		]);
	}

	# destroy
	public function destroy(Request $request, $nonprofit_id, $volunteer_id)
	{
		$volunteer = $this->nonprofit->employees()->findOrFail($volunteer_id);
		$current_role =  $volunteer->nonprofitRole($this->nonprofit->id);

		// owner
		if ($current_role->name == 'nonprofit_owner')
			return response()->json(['message' => 'You are not authorized to perform this action'], 403);

		$this->nonprofit->employees()->detach($volunteer_id);

		return response()->json(['message' => $volunteer->name . ' has been removed']);
	}
}
