<?php

namespace App\Traits;

trait HasCategories {

	# categories
	public function categories()
	{
		return $this->belongsToMany('App\Category')->ordered();
	}

	# update categories
	public function updateCategories($categories)
	{
		$this->categories()->sync($categories);

		// select events in past 30 days and update
	}

	# filter
	public function scopeSearchByCategories($query, $categories)
	{
		return $query->whereHas('categories', function($q) use ($categories) {
			$q->whereIn('categories.id', $categories);
		});
	}

}