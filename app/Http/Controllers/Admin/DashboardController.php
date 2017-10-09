<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
	# dashboard
	public function dashboard()
	{
		return view('admin.dashboard');
	}
}
