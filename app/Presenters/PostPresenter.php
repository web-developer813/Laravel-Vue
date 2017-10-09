<?php

namespace App\Presenters;

use App\Presenters\Presenter;

class PostPresenter extends Presenter
{
	# likes
	public function likes()
	{
		return count($this->entity->likes()->get());
	}
}