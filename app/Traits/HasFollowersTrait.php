<?php

namespace App\Traits;

use App\Follow;

trait HasFollowers {

	# followers
	public function followers()
	{
		return $this->belongsToMany('App\Follow')->ordered();
	}

}