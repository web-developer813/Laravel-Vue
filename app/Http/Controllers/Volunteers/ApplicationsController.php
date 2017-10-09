<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Opportunity;
use App\Application;

class ApplicationsController extends Controller
{
	# store
	public function store(Request $request, $opportunity_id)
	{
		$volunteer = auth()->user()->volunteer;
		$opportunity = Opportunity::published()->findOrFail($opportunity_id);

		Application::create([
			'volunteer_id' => $volunteer->id,
			'opportunity_id' => $opportunity->id,
			'nonprofit_id' => $opportunity->nonprofit_id,
			'volunteer_message' => $request->volunteer_message
		]);

		// flash
		flash('You have applied to this opportunity', 'success');
		return redirect()->route('applications.index');
	}

	# index
	public function index()
	{
		return view('volunteers.applications.index')->with('status', 'accepted');
	}

	# show
	public function show(Request $request, $application_id)
	{
		$application = auth()->user()->volunteer->applications()->findOrFail($application_id);
		$opportunity = $application->opportunity;
		$nonprofit = $opportunity->nonprofit;

		return view('volunteers.applications.show', compact('application', 'opportunity', 'nonprofit'));
	}
}
