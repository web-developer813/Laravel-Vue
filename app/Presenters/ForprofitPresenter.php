<?php

namespace App\Presenters;

use App\Presenters\Presenter;

class ForprofitPresenter extends Presenter
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

	# total hours
	public function total_hours()
	{
		return number_format($this->entity->total_hours);
	}

	# total points
	public function total_points()
	{
		return number_format($this->entity->total_points);
	}

	# total coupons sold
	public function total_coupons_sold()
	{
		return number_format($this->entity->total_coupons_sold);
	}
}