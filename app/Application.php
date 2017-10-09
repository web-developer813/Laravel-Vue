<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Traits\PresentableTrait;
use App\Traits\SearchableTrait;
use App\Notifications\Volunteers\ApplicationAccepted;

class Application extends Model
{
	use PresentableTrait, SearchableTrait, SoftDeletes;

	protected $searchable = ['opportunity' => ['title', 'description'], 'volunteer' => ['name']];

	protected $presenter = 'App\Presenters\ApplicationPresenter';

	protected $fillable = ['nonprofit_id', 'opportunity_id', 'volunteer_id', 'volunteer_message', 'nonprofit_message'];

	protected $table = 'applications';

	protected $dates = ['created_at', 'reviewed_at'];

	protected $appends = ['status', 'url'];

	# get edit url
	public function getEditUrlAttribute()
	{
		return route('nonprofits.applications.edit', [$this->nonprofit_id, $this->id]);
	}

	# get edit url
	public function getUrlAttribute()
	{
		return route('volunteers.applications.show', $this->id);
	}

	# status
	public function getStatusAttribute()
	{
		if ($this->isAccepted())
			return 'accepted';
		elseif ($this->isRejected())
			return 'rejected';
		elseif ($this->isPending())
			return 'pending'; 
	}

	# scope accepted
	public function scopeAccepted($query)
	{
		return $query->whereAccepted(1);
	}

	# is accepted
	public function isAccepted()
	{
		return ($this->accepted) ? true : false;
	}

	# scope rejected
	public function scopeRejected($query)
	{
		return $query->whereAccepted(0)->whereNotNull('reviewed_at');
	}

	# is pending
	public function isRejected()
	{
		return (!$this->accepted && $this->reviewed_at) ? true : false;
	}

	# scope pending
	public function scopePending($query)
	{
		return $query->whereNull('reviewed_at');
	}

	# is pending
	public function isPending()
	{
		return (!$this->reviewed_at) ? true : false;
	}

	# scope ordered
	public function scopeOrdered($query)
	{
		return $query->orderBy('created_at', 'desc')
			->orderBy('id', 'desc');
	}

	# volunteer
	public function volunteer()
	{
		return $this->belongsTo('App\Volunteer');
	}

	# opportunity
	public function opportunity()
	{
		return $this->belongsTo('App\Opportunity');
	}

	# nonprofit
	public function nonprofit()
	{
		return $this->belongsTo('App\Nonprofit');
	}

	# set status
	public function setStatus($value)
	{
		// accepted for the first time
		if (!$this->reviewed_at && $value) {
			$this->accepted = true;
			$this->reviewed_at = Carbon::now();
			$this->save();
			$this->volunteer->notify(new ApplicationAccepted($this));
		} 
		else
		{
			$this->accepted = ($value) ? true : false;
			$this->save();
		}
	}
}
