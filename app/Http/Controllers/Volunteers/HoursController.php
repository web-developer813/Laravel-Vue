<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HoursController extends Controller
{
	# index
	public function index()
	{
		return view('volunteers.hours.index');
	}
}
