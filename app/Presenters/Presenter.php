<?php

namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;

abstract class Presenter 
{
	protected $entity;

	public function __construct(Model $entity)
	{
		$this->entity = $entity;
	}

	public function __get($property)
	{
		if (method_exists($this, $property)) {
			return call_user_func([$this, $property]);
		}

		if ($this->entity->{$property})
			return $this->entity->{$property};

		return '';
	}
}