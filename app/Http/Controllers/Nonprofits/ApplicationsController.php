<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nonprofit;
use App\Application;
use DB;

class ApplicationsController extends Controller
{
	protected $nonprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
	}

	# edit
	public function edit(Request $request, $nonprofit_id, $application_id)
	{
		$application = Application::findOrFail($application_id);
		$opportunity = $application->opportunity;
		$volunteer = $application->volunteer;
		return view('nonprofits.applications.edit', compact('application', 'opportunity', 'volunteer'));
	}

	# update
	public function update(Request $request, $nonprofit_id, $application_id)
	{
		$application = Application::findOrFail($application_id);
		$opportunity = $application->opportunity;
		if ($opportunity->closed && !$application->accepted) {
			$volunteer = $application->volunteer;
			flash('The quantity of accepted applicants is reach for this opportunity!', 'error');
			return redirect()->route('nonprofits.applications.edit', compact('application', 'opportunity', 'volunteer'));
		}

		DB::transaction(function() use ($application, $request) {
			$application->update(['nonprofit_message' => $request->nonprofit_message]);
			$application->setStatus($request->accepted);
		});

		flash('This application has been ' . strtolower($application->present()->status) . '.', 'success');
		return redirect()->route('nonprofits.manage.applications', [$nonprofit_id, $application->opportunity_id]);
	}
}
