<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $table = 'categories';

	protected $visible = [
		'id', 'slug', 'name'
	];

	# ordered
	public function scopeOrdered($query)
	{
		return $query->orderBy('name', 'asc');
	}
}