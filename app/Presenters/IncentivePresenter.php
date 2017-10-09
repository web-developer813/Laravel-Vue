<?php

namespace App\Presenters;

use App\Presenters\Presenter;
use Carbon\Carbon;

class IncentivePresenter extends Presenter
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

	# date
	public function date()
	{
		$created_at = $this->entity->created_at;

		return (strtotime($created_at) < strtotime(Carbon::now()->subWeek()))
			? $created_at->toFormattedDateString()
			: $created_at->diffForHumans();
	}
}