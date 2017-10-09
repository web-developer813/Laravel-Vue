<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hours extends Model
{
	use SoftDeletes;
	
	protected $table = 'hours';

	protected $guarded = ['id'];

	protected $appends = ['delete_url', 'has_multiple_dates', 'formatted_dates'];

	protected $dates = ['start_date', 'end_date'];

	# delete URL
	public function getDeleteUrlAttribute()
	{
		return route('api.nonprofits.hours.destroy', [$this->nonprofit_id, $this->id]);
	}

	# multiple dates boolean
	public function getMultipleDatesAttribute()
	{
		return ($this->start_date && $this->end_date && ($this->start_date !== $this->end_date))
			? true
			: false;
	}

	# multiple dates boolean
	public function getHasMultipleDatesAttribute()
	{
		return ($this->start_date && $this->end_date && ($this->start_date != $this->end_date))
			? true
			: false;
	}

	# get formatted dates attributes
	public function getFormattedDatesAttribute()
	{
		$dates = $this->start_date->format('D M jS, Y');
		if ($this->has_multiple_dates)
			$dates .= " &mdash; {$this->end_date->format('D M jS, Y')}";

		return $dates;
	}

	# scope ordered
	public function scopeOrdered($query)
	{
		return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
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
}
