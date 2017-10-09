<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nonprofit;

class HoursController extends Controller
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
	public function index()
	{
		$opportunities = $this->nonprofit->opportunities;
		$volunteers = $this->nonprofit->volunteers()->whereAccepted(1)->get();
		return view('nonprofits.hours.index', compact('opportunities', 'volunteers'));
	}

	# edit
	public function edit()
	{
		return 'edit hours';
	}
}
