<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nonprofit;

class ProfileController extends Controller
{
	protected $nonprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
	}

	# show
	public function show(Request $request)
	{
		$nonprofit = $this->nonprofit;

		return view('nonprofits.profile', compact('nonprofit'));
	}
}
