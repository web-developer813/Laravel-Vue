<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Invitation;

class InvitationsController extends Controller
{
	# accept
	public function accept(Request $request, $hash)
	{
		$invitation = Invitation::whereAccepted(0)->whereHash($hash)->first();


		if (!$invitation)
		{
			flash('This invitation is not valid', 'error');
			return redirect()->to('register');
		}

		// save invitation to session
		session()->put('invitation', $invitation);

		// set to volunteer mode
		// $request->session()->forget('auth-nonprofit');
		// $request->session()->forget('auth-forprofit');
		auth()->logout();

		$inviter = $invitation->inviter;

		return view('app.invitations.accept', compact('inviter'));
	}
}
