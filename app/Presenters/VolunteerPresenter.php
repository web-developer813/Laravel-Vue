<?php

namespace App\Presenters;

use App\Presenters\Presenter;

class VolunteerPresenter extends Presenter
{
	# location
	public function location()
	{
		// user sprintf('%s %s, %s', $city, $state, $country)
		$v = $this->entity;
		if ($city = $v->city && $state = $v->state && $country = $v->country)
		{
			return "$city $state, $country";
		}
	}

	# description
	public function description()
	{
		return text_format($this->entity->description);
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

	# total points donations
	public function total_points_donations()
	{
		return number_format($this->entity->total_points_donations);
	}
}