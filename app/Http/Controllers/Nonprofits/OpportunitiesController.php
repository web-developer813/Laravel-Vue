<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Category;

class OpportunitiesController extends Controller
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
	public function index(Request $request)
	{
		$opportunities = $this->nonprofit->opportunities;
		return view('nonprofits.opportunities.index', compact('opportunities'));
	}

	# create
	public function create(Request $request)
	{
		$today = Carbon::now()->toDateString();
		$categories = Category::get();
		return view('nonprofits.opportunities.create', compact('today', 'categories'));
	}

	# edit
	public function edit(Request $request, $nonprofit_id, $opportunity_id)
	{
		$opportunity = $this->nonprofit->opportunities()->whereId($opportunity_id)->firstOrFail();
		$start_date = $opportunity->start_date ? $opportunity->start_date->toDateString() : Carbon::now()->toDateString();
		$end_date = $opportunity->end_date ? $opportunity->end_date->toDateString() : Carbon::now()->toDateString();
		$categories = Category::get();

		return view('nonprofits.opportunities.edit', compact('opportunity', 'start_date', 'end_date', 'categories'));
	}
}
