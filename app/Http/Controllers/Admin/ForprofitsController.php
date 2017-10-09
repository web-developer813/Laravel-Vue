<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Requests\Admin\UpdateForprofitRequest;
use App\Helpers\Services\LocationService;
use App\Http\Controllers\Controller;
use App\Forprofit;
use DB;

class ForprofitsController extends Controller
{
	# index
	public function index(Request $request)
	{
		$query = Forprofit::query()->orderBy('name', 'asc')->orderBy('id', 'desc');

		// filter verified
		if ($request->has('verified'))
			$query->whereVerified($request->verified);

		$forprofits = $query->paginate('30');

		// add query parameters
		$forprofits->appends($request->except('page'));

		return view('admin.forprofits.index', compact('forprofits'));
	}

	# edit
	public function edit(Request $request, $forprofit_id)
	{
		$forprofit = Forprofit::findOrFail($forprofit_id);

		return view('admin.forprofits.edit', compact('forprofit'));
	}

	# update
	public function update(UpdateForprofitRequest $request, $forprofit_id)
	{
		$forprofit = Forprofit::findOrFail($forprofit_id);

		$input = $request->all();
		$input = array_merge($input, LocationService::getFullAddress($request->location, $forprofit->location, false));

		DB::transaction(function() use ($forprofit, $input, $request) {
			$forprofit->update($input);
			$forprofit->setVerified($request->verified);
		});

		// response
		flash($forprofit->name . ' has been updated', 'success');
		return redirect()->route('admin.forprofits.edit', $forprofit->id);
	}
}
