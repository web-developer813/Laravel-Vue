<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Incentive;

class IncentivesController extends Controller
{
	# show
	public function show(Request $request, $incentive_id)
	{
		$incentive = Incentive::findOrFail($incentive_id);

		return view('volunteers.incentives.show', compact('incentive'));
	}
}
