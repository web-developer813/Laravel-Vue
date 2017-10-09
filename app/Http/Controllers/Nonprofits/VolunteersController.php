<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nonprofit;

class VolunteersController extends Controller
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
		$volunteers = $this->nonprofit->volunteers;
		return view('nonprofits.volunteers.index', compact('volunteers'));
	}
	
	# invitations index
	public function invitations_index()
	{
		return view('nonprofits.volunteers.invitations.index');
	}
	
	# invitations create
	public function invitations_create()
	{
		return view('nonprofits.volunteers.invitations.create');
	}
}
