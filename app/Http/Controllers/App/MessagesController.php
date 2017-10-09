<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class MessagesController extends Controller
{
	public function index()
	{
		return view('volunteers.messages.index');
	}
}
