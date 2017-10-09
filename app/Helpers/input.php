<?php

function input_only(stdClass $input, $parameters)
{
	$data = [];

	// \Log::info($parameters)

	foreach($parameters as $key => $param)
	{
		if (is_numeric($key))
		{
			$data[$param] = isset($input->{$param}) ? $input->{$param} : null;
			continue;
		}
		$data[$key] = isset($input->{$param}) ? $input->{$param} : null;
	}

	return array_filter($data);
}

function input_get(stdClass $input, $param)
{
	return isset($input->{$param}) ? $input->{$param} : null;
}

// for generators
function uniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}