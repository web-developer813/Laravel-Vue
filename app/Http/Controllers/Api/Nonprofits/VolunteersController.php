<?php

namespace App\Http\Controllers\Api\Nonprofits;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class VolunteersController extends ApiController
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
		$query = $this->nonprofit->volunteers();
		
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
				// 'role' => $volunteer->nonprofitRole($this->nonprofit->id)->toArray(),
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($volunteers->nextPageUrl())
			]);
	}
}
