<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Volunteer;
use App\Nonprofit;
use App\Opportunity;
use App\User;
use Auth;

class VolunteersController extends Controller
{
	# show
	public function show(Request $request, $volunteer_id)
	{
		$user = $request->user()->volunteer;
		$volunteer = Volunteer::wherePublicProfile(1)->findOrFail($volunteer_id);

		// Get Connected Status
		$volunteer->connected = $user->getFriendship($volunteer) ? $user->getFriendship($volunteer) : 0;

		$volunteer->connections = $volunteer->getFriends();

		$volunteer->requests = $volunteer->getFriendRequests();

		$donations = [];
		if ($volunteer->donations->count()) {
			foreach ($volunteer->donations as $donation) {
				$d = $donation->toArray();
				$d['nonprofit'] = $donation->nonprofit;
				$d['created_at'] = $donation->created_at->diffForHumans();
				$donations[] = $d;
			}
		}

		$volunteer->donations = array_slice($donations,0,5);

		$hours = [];
		if ($volunteer->hours->count()) {
			foreach ($volunteer->hours as $hour) {
				$h = $hour->toArray();
				$h['nonprofit'] = Nonprofit::findOrFail($hour->nonprofit_id);
				$h['opportunity'] = Opportunity::findOrFail($hour->opportunity_id);
				$h['created_at'] = $hour->created_at->diffForHumans();
				$hours[] = $h;
			}
		}

		$volunteer->hours = array_slice($hours, 0,5);
		
		$volunteer->following = 0;
		$volunteer->followable_id = 0;
		$volunteer->follow_id = 0;
		if($authUserIsFollowing = $request->user()->volunteer->following()->where([['followable_id','=',$volunteer_id],['followable_type','=','App\\Volunteer']])->first()) {
			$volunteer->following = true;
			$volunteer->followable_id = $authUserIsFollowing->followable_id;
			$volunteer->follow_id = $authUserIsFollowing->id;
		}
		
		return view('volunteers.volunteers.show', compact('volunteer'));
	}

	# donations
	public function donations(Request $request, $volunteer_id)
	{
		$volunteer = Volunteer::wherePublicProfile(1)->findOrFail($volunteer_id);

		return view('volunteers.volunteers.donations', compact('volunteer'));
	}
}
