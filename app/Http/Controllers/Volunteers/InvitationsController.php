<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvitationsController extends Controller
{
	# create
	public function create()
	{
		return view('volunteers.invitations.create');
	}

	# store
	public function store(Request $request)
	{

	}
}
