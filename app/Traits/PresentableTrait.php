<?php

namespace App\Traits;

trait PresentableTrait {
	public function present()
	{
		return new $this->presenter($this);
	}
}