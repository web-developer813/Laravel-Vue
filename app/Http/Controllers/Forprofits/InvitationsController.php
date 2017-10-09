<?php

namespace App\Http\Controllers\Forprofits;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Requests\Forprofits\StoreCsvInvitationRequest;
use App\Http\Requests\Forprofits\StoreEmailsInvitationRequest;
use App\Http\Controllers\Controller;
use App\Invitation;
use Excel;

class InvitationsController extends Controller
{
	protected $forprofit;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->forprofit = config()->get('authForprofit');
            return $next($request);
        });
	}
	
	# index
	public function index()
	{
		return view('forprofits.invitations.index');
	}
	
	# create
	public function create()
	{
		return view('forprofits.invitations.create');
	}

	# store csv
	public function store_csv(StoreCsvInvitationRequest $request)
	{
		Excel::load($request->file('csv_import')->getRealPath(), function($reader) {
			$reader->each(function($sheet) {    
				foreach ($sheet->toArray() as $row)
				{
					if (filter_var($row, FILTER_VALIDATE_EMAIL))
						$this->inviteEmail($row);
				}
			});
		});

		flash('Your CSV has been imported and your invitations are being sent out.', 'success');
		return redirect()->route('forprofits.invitations.create', $this->forprofit->id);
	}

	# store emails
	public function store_emails(StoreEmailsInvitationRequest $request)
	{
		$emails = array_filter(preg_split('/\n|\r\n?/', $request->emails));

		foreach($emails as $email) {
			if (filter_var($email, FILTER_VALIDATE_EMAIL))
				$this->inviteEmail($email);
		}

		flash('Your emails has been imported and your invitations are being sent out.', 'success');
		return redirect()->route('forprofits.invitations.create', $this->forprofit->id);
	}

	# invite email
	protected function inviteEmail($email)
	{
		// create invitation
		$invitation = new invitation([
			'email' => $email,
			'expires_at' => Carbon::now()->addMonth()->toDateTimeString(),
			'type' => 'employee'
		]);

		try
		{
			$this->forprofit->invitations()->save($invitation);
		}

		catch(QueryException $e)
		{
			// duplicate, do nothing
		}
	}
}
