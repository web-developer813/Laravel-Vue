<?php

namespace App\Presenters;

use App\Presenters\Presenter;

class NonprofitPresenter extends Presenter
{
	# excerpt
	public function excerpt()
	{
		return excerpt(trim(strip_tags($this->entity->mission)), 125);
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
			return $this->entity->location_city . ', ' . $this->entity->location_state;
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

	# points
	public function points()
	{
		return number_format($this->entity->points);
	}

	# total hours
	public function total_hours()
	{
		return number_format($this->entity->total_hours);
	}

	# total volunteers
	public function total_volunteers()
	{
		return number_format($this->entity->total_volunteers);
	}
}