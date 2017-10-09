<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmployeesController extends Controller
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
		return view('nonprofits.employees.index');
	}
	
	# invitations index
	public function invitations_index()
	{
		return view('nonprofits.employees.invitations.index');
	}
	
	# invitations create
	public function invitations_create()
	{
		return view('nonprofits.employees.invitations.create');
	}
}
