<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Requests\Nonprofits\StoreCsvInvitationRequest;
use App\Http\Requests\Nonprofits\StoreEmailsInvitationRequest;
use App\Http\Controllers\Controller;
use App\Invitation;
use Excel;

class InvitationsController extends Controller
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
	public function index_for_volunteers()
	{
		return view('nonprofits.volunteers.invitations.index');
	}
	
	# create
	public function create_for_volunteers()
	{
		return view('nonprofits.volunteers.invitations.create');
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

		return ($request->invitation_type == 'volunteer')
			? redirect()->route('nonprofits.volunteers.invitations.create', $this->nonprofit->id)
			: redirect()->route('nonprofits.employees.invitations.create', $this->nonprofit->id);
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
		
		return ($request->invitation_type == 'volunteer')
			? redirect()->route('nonprofits.volunteers.invitations.create', $this->nonprofit->id)
			: redirect()->route('nonprofits.employees.invitations.create', $this->nonprofit->id);
	}

	# invite email
	protected function inviteEmail($email)
	{
		// create invitation
		$invitation = new invitation([
			'email' => $email,
			'expires_at' => Carbon::now()->addMonth()->toDateTimeString(),
			'type' => request()->invitation_type
		]);

		try
		{
			$this->nonprofit->invitations()->save($invitation);
		}

		catch(QueryException $e)
		{
			// duplicate, do nothing
		}
	}
}
