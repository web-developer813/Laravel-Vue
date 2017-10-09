<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Forprofit;

class ForprofitsController extends Controller
{
	# index
	public function index()
	{
		$forprofits = Forprofit::verified()->ordered()->get();

		return view('nonprofits.forprofits.index', compact('forprofits'));
	}
}
