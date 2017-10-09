<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VolunteersController extends Controller
{
	# index
	public function index()
	{
		return view('admin.volunteers.index');
	}
}
