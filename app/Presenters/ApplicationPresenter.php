<?php

namespace App\Presenters;

use App\Presenters\Presenter;
use Carbon\Carbon;

class ApplicationPresenter extends Presenter
{
	# css status
	public function css_status()
	{
		if ($this->entity->isAccepted())
			return 'status--positive';
		elseif ($this->entity->isRejected())
			return 'status--negative';
		elseif ($this->entity->isPending())
			return 'status--neutral';
	}

	# status
	public function status()
	{
		if ($this->entity->isAccepted())
			return 'Accepted';
		elseif ($this->entity->isRejected())
			return 'Rejected';
		elseif ($this->entity->isPending())
			return 'Pending';
	}

	# date
	public function date()
	{
		$created_at = $this->entity->created_at;

		return (strtotime($created_at) < strtotime(Carbon::now()->subWeek()))
			? "on " . $created_at->toFormattedDateString()
			: $created_at->diffForHumans();
	}

	# volunteer message
	public function volunteer_message()
	{
		return text_format($this->entity->volunteer_message);
	}

	# nonprofit message
	public function nonprofit_message()
	{
		return text_format($this->entity->nonprofit_message);
	}
}