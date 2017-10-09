<?php

namespace App\Presenters;

use App\Presenters\Presenter;
use Carbon\Carbon;

class OpportunityPresenter extends Presenter
{
	# excerpt
	public function excerpt()
	{
		return excerpt($this->entity->description, 125);
	}

	# description
	public function description()
	{
		return text_format($this->entity->description);
	}

	# location
	public function location()
	{
		if ($this->entity->has_location)
			return $this->entity->location_address . ', ' . $this->entity->location_city . ', ' . $this->entity->location_state;
		return null;
	}

	# distance
	public function distance()
	{
		$caption = 'miles';
		$distance = floor($this->entity->distance);
		if ($distance == 0) { $distance = 'less than a'; $caption = 'mile'; }
		if ($distance == 1) { $caption = 'mile'; }
		return "$distance $caption";
	}

	# date
	public function date()
	{
		$created_at = $this->entity->created_at;

		return (strtotime($created_at) < strtotime(Carbon::now()->subWeek()))
			? $created_at->toFormattedDateString()
			: $created_at->diffForHumans();
	}

	# status
	public function status()
	{
		return ($this->entity->isPublished()) ? 'Published' : 'Draft';
	}
}