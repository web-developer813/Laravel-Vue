<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
	protected $items = [];

	# paginate
	protected function paginate($query, $request, $per_page = 20)
	{
		$results = $query->paginate(18);
		$results->appends($request->except('page'));
		return $results;
	}

	# search
	protected function search($query, $request)
	{
		if ($request->search)
			return $query->search($request->search);
		return $query;
	}

	# count
	protected function count($query)
	{
		return $query->count();
	}
}
