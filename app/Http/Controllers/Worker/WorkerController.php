<?php

namespace App\Http\Controllers\Worker;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artisan;

class WorkerController extends Controller
{
	// schedule
	public function schedule()
	{
		Artisan::call('schedule:run');
	}
}
