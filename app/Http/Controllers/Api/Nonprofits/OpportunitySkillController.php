<?php

namespace App\Http\Controllers\Api\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Opportunity;
use App\NonprofitOpportunitySkill;
use DB;

class OpportunitySkillController extends ApiController
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
	public function index(Request $request, $nonprofit, $opportunity_id)
	{
		$skills = NonprofitOpportunitySkill::select(['id', 'name'])
			->where('opportunity_id', $opportunity_id)
			->orderBy('order')
			->get();
		
		return response()->json([
			'success' => true,
			'items' => $skills
		]);
	}

	# store
	public function store(Request $request, $nonprofit, $opportunity_id)
	{
		$opportunity = Opportunity::where('nonprofit_id', $this->nonprofit->id)
			->where('id', $opportunity_id)
			->first();
		if (!$opportunity) {
			return response()->json([
				'success' => false,
				'message' => 'Permission denied'
			], 550);
		}

		$skill = NonprofitOpportunitySkill::where('name', $request->get('skill'))
			->where('opportunity_id', $opportunity_id)
			->first();
		
		if ($skill) {
			return response()->json([
				'success' => false,
				'message' => 'skill already exist'
			], 400);
		}
		
		$skill = new NonprofitOpportunitySkill();
		$skill->name = $request->get('skill');
		$skill->opportunity_id = $opportunity_id;
		$skill->save();
		$skill->order = $skill->id;
		$skill->save();

		$item = NonprofitOpportunitySkill::select(['id', 'name'])
			->where('id', $skill->id)
			->first();

		return response()->json([
			'success' => true,
			'item' => $item
		]);
	}

	# Update
	public function update(Request $request, $nonprofit, $opportunity_id)
	{
		$opportunity = Opportunity::where('nonprofit_id', $this->nonprofit->id)
			->where('id', $opportunity_id)
			->first();
		if (!$opportunity) {
			return response()->json([
				'success' => false,
				'message' => 'Permission denied'
			], 550);
		}

		$arrKeptSkillsId = $request->get('skills_kept');
		
		NonprofitOpportunitySkill::where('opportunity_id', $opportunity_id)
			->whereNotIn('id', $arrKeptSkillsId)
			->delete();
		
		$order = 0;
		foreach($arrKeptSkillsId as $keptSkillId) {
			$skill = NonprofitOpportunitySkill::where('opportunity_id', $opportunity_id)
				->where('id', $keptSkillId)
				->first();
			if ($skill) {
				$skill->order = $order;
				$order++;
				$skill->save();
			}
		}
		
		$skills = NonprofitOpportunitySkill::select(['id', 'name'])
			->where('opportunity_id', $opportunity_id)
			->orderBy('order')
			->get();

		return response()->json([
			'success' => true,
			'items' => $skills
		]);
	}
}
