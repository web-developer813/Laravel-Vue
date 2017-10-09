<?php

namespace App\Http\Controllers\Api\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Role;

class EmployeesController extends ApiController
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
	public function index(Request $request, $forprofit_id)
	{
		$query = $this->forprofit->employees();
		
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
					'update_url' => route('api.forprofits.employees.update', [$this->forprofit->id, $volunteer->id]),
					'delete_url' => route('api.forprofits.employees.delete', [$this->forprofit->id, $volunteer->id]),
				],
				'role' => $volunteer->forprofitRole($this->forprofit->id)->toArray(),
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($volunteers->nextPageUrl())
			]);
	}

	# update
	public function update(Request $request, $forprofit_id, $volunteer_id)
	{
		$role = Role::whereName($request->role)->firstOrFail();
		$volunteer = $this->forprofit->employees()->findOrFail($volunteer_id);
		$current_role =  $volunteer->forprofitRole($this->forprofit->id);

		// owner
		if ($current_role->name == 'forprofit_owner')
			return response()->json(['message' => 'You are not authorized to perform this action'], 403);

		$this->forprofit->employees()->detach($volunteer->id);
		$this->forprofit->employees()->attach($volunteer->id, [
			'user_id' => $volunteer->user_id,
			'role_id' => $role->id
		]);

		return response()->json([
			'message' => $volunteer->name . ' has been updated',
			'item' => [
				'volunteer' => $volunteer->toArray(),
				'admin' => [
					'update_url' => route('api.forprofits.employees.update', [$this->forprofit->id, $volunteer->id]),
					'delete_url' => route('api.forprofits.employees.delete', [$this->forprofit->id, $volunteer->id]),
				],
				'role' => $volunteer->forprofitRole($this->forprofit->id)->toArray(),
			]
		]);
	}

	# destroy
	public function destroy(Request $request, $forprofit_id, $volunteer_id)
	{
		$volunteer = $this->forprofit->employees()->findOrFail($volunteer_id);
		$current_role =  $volunteer->forprofitRole($this->forprofit->id);

		// owner
		if ($current_role->name == 'forprofit_owner')
			return response()->json(['message' => 'You are not authorized to perform this action'], 403);

		$this->forprofit->employees()->detach($volunteer_id);

		return response()->json(['message' => $volunteer->name . ' has been removed']);
	}
}
