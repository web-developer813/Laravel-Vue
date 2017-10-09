<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Admin\UpdateNonprofitRequest;
use App\Helpers\Services\LocationService;
use App\Http\Controllers\Controller;
use App\Nonprofit;
use DB;

class NonprofitsController extends Controller
{
	# index
	public function index(Request $request)
	{
		$query = Nonprofit::query()->orderBy('name', 'asc')->orderBy('id', 'desc');

		// filter verified
		if ($request->has('verified'))
			$query->whereVerified($request->verified);

		$nonprofits = $query->paginate('30');

		// add query parameters
		$nonprofits->appends($request->except('page'));

		return view('admin.nonprofits.index', compact('nonprofits'));
	}

	# edit
	public function edit(Request $request, $nonprofit_id)
	{
		$nonprofit = Nonprofit::findOrFail($nonprofit_id);

		return view('admin.nonprofits.edit', compact('nonprofit'));
	}

	# update
	public function update(UpdateNonprofitRequest $request, $nonprofit_id)
	{
		$nonprofit = Nonprofit::findOrFail($nonprofit_id);

		$input = $request->all();
		$input = array_merge($input, LocationService::getFullAddress($request->location, $nonprofit->location, false));

		DB::transaction(function() use ($nonprofit, $input, $request) {
			$nonprofit->update($input);
			$nonprofit->setVerified($request->verified);
			// $this->nonprofit->updateFile($request->file('file_501c3'), 'file_501c3');
		});
		
		flash($nonprofit->name . ' has been updated', 'success');
		return redirect()->route('admin.nonprofits.edit', $nonprofit->id);
	}
}
