<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nonprofit;
use App\Forprofit;

class ModeController extends Controller
{
	# switch
	public function switch_mode(Request $request, $mode)
	{
		// clear sessions
		$request->session()->forget('auth-nonprofit');
		$request->session()->forget('auth-forprofit');

		switch ($mode)
		{
			case 'nonprofit':
				$nonprofit = auth()->user()->nonprofitsWithAdminAccess()->find($request->nonprofit);
				if ($nonprofit)
				{
					$request->session()->put('auth-nonprofit', $nonprofit);
					return ($request->next)
						? redirect()->to($request->next)
						: redirect()->route('nonprofits.dashboard', $nonprofit->id);
				}
			case 'forprofit':
				$forprofit = auth()->user()->forprofitsWithAdminAccess()->find($request->forprofit);
				if ($forprofit)
				{
					$request->session()->put('auth-forprofit', $forprofit);
					return ($request->next)
						? redirect()->to($request->next)
						: redirect()->route('forprofits.dashboard', $forprofit->id);
				}
		}

		// volunteers and default
		return redirect()->route('dashboard');
	}
}
