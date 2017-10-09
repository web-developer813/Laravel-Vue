<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

use App\Traits\HashableTrait;
use App\Traits\SearchableTrait;
use App\Mail\Nonprofits\InvitationToVolunteers;
use App\Mail\Nonprofits\InvitationToEmployees as NonprofitsInvitationToEmployees;
use App\Mail\Forprofits\InvitationToEmployees as ForprofitsInvitationToEmployees;
use App\Volunteer;
use App\Nonprofit;
use App\Forprofit;
use App\Role;
use Mail;

class Invitation extends Model
{
	use HashableTrait, SearchableTrait, SoftDeletes;

	protected $searchable = ['email'];

	protected $guarded = [];

	protected $appends = ['send_url', 'delete_url'];

	# get send url
	public function getSendUrlAttribute()
	{
		if (is_a($this->inviter, 'App\Nonprofit'))
			return route('api.nonprofits.invitations.send', [$this->inviter_id, $this->id]);

        elseif (is_a($this->inviter, 'App\Forprofit'))
			return route('api.forprofits.invitations.send', [$this->inviter_id, $this->id]);
	}

	# get delete url
	public function getDeleteUrlAttribute()
	{
		if (is_a($this->inviter, 'App\Nonprofit'))
			return route('api.nonprofits.invitations.delete', [$this->inviter_id, $this->id]);

        elseif (is_a($this->inviter, 'App\Forprofit'))
			return route('api.forprofits.invitations.delete', [$this->inviter_id, $this->id]);
	}

	# inviter
	public function inviter()
	{
		return $this->morphTo();
	}

	# scope accepted
	public function scopeAccepted($query)
	{
		return $query->whereAccepted(1);
	}

	# scope pending
	public function scopePending($query)
	{
		return $query->whereAccepted(0);
	}

	# scope pending
	public function scopeExpired($query)
	{
		return $query->whereAccepted(0)->where('expired_at', '<', Carbon::now());
	}

	# scope ordered
	public function scopeOrdered($query)
	{
		return $query->orderBy('created_at', 'desc')
			->orderBy('id', 'desc');
	}

	# accepted by
	public function acceptedBy($user_id)
	{
        $inviter = $this->inviter;

        // already accepted
        if ($this->accepted) return false;

        // forprofit invite
        try
        {
        	// employees
        	if ($this->type == 'employee')
        	{
        		if ($inviter instanceof Forprofit)
        			$role = Role::whereName('forprofit_employee')->firstOrFail();

        		elseif ($inviter instanceof Nonprofit)
        			$role = Role::whereName('nonprofit_employee')->firstOrFail();

		        $inviter->employees()->attach(auth()->user()->volunteer->id, [
					'user_id' => auth()->id(),
					'role_id' => $role->id
				]);
        	}
        }

        catch (QueryException $e)
        {
        	// already with the company / nonrpofit
        	// do nothing
        }

        // mark as accepted
		$this->update(['accepted' => 1, 'user_id' => $user_id]);

        // remove from session
        session()->forget('invitation');
	}

	# send
	public function send()
	{
		// inviter
		$inviter = $this->inviter;

		// nonprofits
		if (is_a($inviter, 'App\Nonprofit'))
		{
		   // volunteers
		   if ($this->type == 'volunteer')
		   {
		       Mail::send(new InvitationToVolunteers($this, $inviter));
		       $this->update(['sent' => true]);
		   }
		   // employees
		   else if($this->type == 'employee')
		   {
		       Mail::send(new NonprofitsInvitationToEmployees($this, $inviter));
		       $this->update(['sent' => true]);
		   }
		}

		// forprofits
		elseif (is_a($inviter, 'App\Forprofit'))
		{
		   Mail::send(new ForprofitsInvitationToEmployees($this, $inviter));
		   $this->update(['sent' => true]);
		}
	}
}
