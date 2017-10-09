<?php

namespace App\Http\Controllers\Api\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Nonprofits\StoreOpportunityRequest;
use App\Http\Requests\Nonprofits\UpdateOpportunityRequest;
use App\Http\Controllers\ApiController;
use App\Helpers\Services\LocationService;
use App\Opportunity;
use DB;

class OpportunitiesController extends ApiController
{
	protected $nonprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
	}

	# index
	public function index(Request $request, $nonprofit_id)
	{
		$query = $this->nonprofit->opportunities();
		
		// search
		if ($request->search)
			$query->search($request->search);

		// ordering
		$query->orderedByCreationDate();

		// pagination
		$opportunities = $this->paginate($query, $request);

		// items
		foreach($opportunities as $opportunity)
		{
			$this->items[] = [
				'opportunity' => $opportunity->toArray(),
				'admin' => [
					'edit_url' => route('nonprofits.opportunities.edit', [$this->nonprofit->id, $opportunity->id]),
					'applications_url' => route('nonprofits.manage.applications', [$this->nonprofit->id, $opportunity->id]),
					'verify_hours_url' => route('nonprofits.manage.verify-hours', [$this->nonprofit->id, $opportunity->id]),
					'applications_count' => count($opportunity->applications()->pending()->get())
				]
			];
		}

		return response()->json([
				'items' => $this->items,
				'nextPageUrl' => nextPageUrl($opportunities->nextPageUrl())
		]);
	}

	# store
	public function store(StoreOpportunityRequest $request)
	{
		$input = $request->only(
			'title', 'description',
			'location', 'location_suite', 'flexible', 'start_date', 'end_date', 'hours_estimate',
			'contact_name', 'contact_email', 'contact_phone',
			'virtual', 'max_accepted_applicant');
		$input['nonprofit_id'] = $this->nonprofit->id;
		$input = array_merge($input, LocationService::getFullAddress($request->location));
		$categories = $request->input('categories', []);
		
		// create nonprofit
		$opportunity = DB::transaction(function() use ($input, $categories, $request) {
			$opportunity = Opportunity::create(array_filter($input));
			$opportunity->updateImage($request->file('photo'));
			$opportunity->categories()->sync($categories);
			$opportunity->setPublished($request->published);
			return $opportunity;
		});

		// return redirect url
		flash('This opportunity has been created', 'success');
		return response()->json(['redirect_url' => route('nonprofits.opportunities.index', $opportunity->nonprofit_id)]);
	}

	# update
	public function update(UpdateOpportunityRequest $request, $nonprofit_id, $opportunity_id)
	{
		// opportunity
		$opportunity = $this->nonprofit->opportunities()->whereId($opportunity_id)->firstOrFail();

		// input
		$input = $request->only(
			'title', 'description',
			'location', 'location_suite', 'flexible', 'start_date', 'end_date', 'hours_estimate',
			'contact_name', 'contact_email', 'contact_phone',
			'virtual', 'max_accepted_applicant');
		$input = array_merge($input, LocationService::getFullAddress($request->location, $opportunity->location));
		$categories = $request->input('categories', []);

		// update opportunity
		DB::transaction(function() use ($opportunity, $input, $categories, $request) {
			$opportunity->update($input);
			$opportunity->updateImage($request->file('photo'));
			$opportunity->categories()->sync($categories);
			$opportunity->setPublished($request->published);
		});

		return response()->json(['message' => 'Your changes have been saved']);
	}
}
