<?php

namespace App\Traits;

trait SearchableTrait {

	# search
	# protected $searchable = ['name', 'client' => ['name', 'email']];
	public function scopeSearch($query, $input)
	{
		$terms = explode(' ', trim($input));

		// no terms
		if (!count($terms)) return $query;

		// search each term for each searchable fields
	    foreach($terms as $term)
	    {
	        $query->where(function($query) use ($term) {
	        	foreach($this->searchable as $relation  => $field)
	        	{
	        		// on a relationship
	        		// search on relationships require full text search on field
	        		if (is_array($field))
	        		{
	        			// fields to search
	        			$match_fields = implode(',', $field);

	        			// full text search query
	        			$query->orWhereHas($relation, function($q) use ($match_fields, $term) {
		        			$q->whereRaw("
								MATCH($match_fields) AGAINST('?' IN BOOLEAN MODE)
	        				", [$term . '*']);
						});

	        			continue;
	        		}

	    			// on the entity
    				$query->orWhere($field, 'like', '%' . $term . '%');
	        	}
	        });
	    }
	}

}