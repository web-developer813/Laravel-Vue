<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\User;
use App\Skill;
use App\SkillEndorsement;
use App\Volunteer;
use App\NonProfit;
use App\Forprofit;
use Auth;

class SkillsController extends ApiController
{
	const VOLUNTEER = 'volunteer';
	const NONPROFIT = 'nonprofit';
	const FORPROFIT = 'forprofit';

	# show
	public function index(Request $request, $volunteer_id)
	{
		$skills = Skill::select(['id', 'name'])
			->where('volunteer_id', $volunteer_id)
			->orderBy('order')
			->with('skill_endorsements')
			->get();
		return response()->json([
			'items' => $skills
		]);
	}

	# store
	public function store(Request $request, $volunteer_id)
	{
		$volunteer = Volunteer::find($volunteer_id);
		$user = Auth::user();
		if ($user->id != $volunteer->user_id) {
			return response()->json([
				'success' => false,
				'message' => 'Permission denied'
			], 550);
		}
		if (!$request->get('skill')) {
			return response()->json([
				'success' => false,
				'message' => 'skill is required'
			], 400);
		}

		$skill = Skill::where('name', $request->get('skill'))
			->where('volunteer_id', $volunteer_id)
			->first();
		
		if ($skill) {
			return response()->json([
				'success' => false,
				'message' => 'skill already exist'
			], 400);
		}
		
		$skill = new Skill();
		$skill->name = $request->get('skill');
		$skill->volunteer_id = $volunteer_id;
		$skill->save();
		$skill->order = $skill->id;
		$skill->save();

		$item = Skill::select(['id', 'name'])
			->where('id', $skill->id)
			->with('skill_endorsements')
			->first();

		return response()->json([
			'success' => true,
			'item' => $item
		]);
	}

	# Update
	public function update(Request $request, $volunteer_id)
	{
		$volunteer = Volunteer::findOrFail($volunteer_id);
		$user = Auth::user();
		if ($user->id != $volunteer->user_id) {
			return response()->json([
				'success' => false,
				'message' => 'Permission denied'
			], 550);
		}

		$arrKeptSkillsId = $request->get('skills_kept');
		
		$skillsDeleted = Skill::where('volunteer_id', $volunteer_id)
			->whereNotIn('id', $arrKeptSkillsId)
			->get();

		foreach($skillsDeleted as $skill) {
			$skill->skill_endorsements()->delete();
			$skill->delete();
		}
		
		$order = 0;
		foreach($arrKeptSkillsId as $keptSkillId) {
			$skill = Skill::where('volunteer_id', $volunteer_id)
				->where('id', $keptSkillId)
				->first();
			if ($skill) {
				$skill->order = $order;
				$order++;
				$skill->save();
			}
		}
		
		$skills = Skill::select(['id', 'name'])
			->where('volunteer_id', $volunteer_id)
			->orderBy('order')
			->with('skill_endorsements')
			->get();

		return response()->json([
			'success' => true,
			'items' => $skills
		]);
	}

	# Add/Remove Endorsement for skill
	public function addOrRemoveEndorsement(Request $request, $skill_id)
	{
		$skill = Skill::findOrFail($skill_id);

		if (current_mode() === self::VOLUNTEER) {
			$volunteer = Volunteer::find($skill->volunteer_id);
			$user = Auth::user();
			if ($user->id == $volunteer->user_id) {
				return response()->json([
					'success' => false,
					'message' => 'Permission denied'
				], 550);
			}
		}

		$isRemoveEndorsement = false;
		
		if (current_mode() === self::NONPROFIT) {
			$type_endorser = self::NONPROFIT;
			$endorser_id = session()->get('auth-nonprofit')->fresh()->id;
		} else if (current_mode() === self::FORPROFIT) {
			$type_endorser = self::FORPROFIT;
			$endorser_id = session()->get('auth-forprofit')->fresh()->id;
		} else {
			$type_endorser = self::VOLUNTEER;
			$endorser_id = config()->get('authVolunteer')->id;
		}

		$skillEndorsement = SkillEndorsement::where('type_endorser', $type_endorser)
			->where('endorser_id', $endorser_id)
			->where('skill_id', $skill_id)
			->first();
		if ($skillEndorsement) {
			$isRemoveEndorsement = true;
			DB::table('skill_endorsements')
				->where('type_endorser', '=', $type_endorser)
				->where('endorser_id', '=', $endorser_id)
				->where('skill_id', '=', $skill_id)
				->delete();
		} else {
			$skillEndorsement = new SkillEndorsement();
			$skillEndorsement->type_endorser = $type_endorser;
			$skillEndorsement->endorser_id = $endorser_id;
			$skillEndorsement->skill_id = $skill_id;
			$skillEndorsement->save();
		}
		return response()->json([
			'success' => true,
			'isRemoveEndorsement' => $isRemoveEndorsement,
			'skillEndorsement' => $skillEndorsement
		]);
	}

	# Get Endorsers
	public function getEndorsers(Request $request, $skill_id)
	{
		$skill = Skill::findOrFail($skill_id);
		$skill_endorsements = $skill->skill_endorsements;
		$listVolunteer = [];
		$listNonprofit = [];
		$listForprofit = [];

		foreach($skill_endorsements as $skill_endorsement) {
			if ($skill_endorsement->type_endorser == self::VOLUNTEER)
				array_push($listVolunteer, $skill_endorsement->endorser_id);
			else if ($skill_endorsement->type_endorser == self::NONPROFIT)
				array_push($listNonprofit, $skill_endorsement->endorser_id);
			else
				array_push($listForprofit, $skill_endorsement->endorser_id);
		}

		$listVolunteer = Volunteer::whereIn('id', $listVolunteer)->get();
		$listNonprofit = Nonprofit::whereIn('id', $listNonprofit)->get();
		$listForprofit = Forprofit::whereIn('id', $listForprofit)->get();

		return response()->json([
			'success' => true,
			'listVolunteer' => $listVolunteer,
			'listNonprofit' => $listNonprofit,
			'listForprofit' => $listForprofit
		]);
	}
}
