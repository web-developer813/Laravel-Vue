<?php

namespace App\Http\Controllers\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Forprofit;

class EmployeesController extends Controller
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
	public function index()
	{
		return view('forprofits.employees.index');
	}
}
