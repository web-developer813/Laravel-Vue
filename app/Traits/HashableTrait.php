<?php

namespace App\Traits;

trait HashableTrait {

	# scope where hash
	public function ScopeWhereHash($query, $hash)
	{
		$query->whereRaw("BINARY `hash`= ?", [$hash]);
	}
	
}