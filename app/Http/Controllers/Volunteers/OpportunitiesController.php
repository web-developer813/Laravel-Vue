<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Opportunity;

class OpportunitiesController extends Controller
{
	# index
	public function index(Request $request)
	{
		return view('volunteers.opportunities.index');
	}

	# show
	public function show(Request $request, $id)
	{
		$opportunity = Opportunity::published()->with('nonprofit')->findOrFail($id);
		// dd($opportunity);
		return view('volunteers.opportunities.show', compact('opportunity'));
	}
}
