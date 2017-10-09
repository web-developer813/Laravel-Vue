<?php

namespace App\Traits;

use Illuminate\Http\Exception\HttpResponseException;

use App\Jobs\GeocodeLocation;
use App\Opportunity;
use DB;
use Geocoder\Laravel\Facades\Geocoder;

trait HasLocationTrait {

	/**
	 * @param $query
	 * @param $lat
	 * @param $lng
	 * @param $radius numeric
	 * @param $units string|['K', 'M']
	 */
	public function scopeNearLatLng($query, $lat, $lng, $radius = 50, $unit = 'M')
	{
		$distanceUnit = ($unit == 'M') ? 69 : 111.045;

		// $haversine = sprintf('*, (%f * DEGREES(ACOS(COS(RADIANS(%f)) * COS(RADIANS(location_lat)) * COS(RADIANS(%f - location_lng)) + SIN(RADIANS(%f)) * SIN(RADIANS(location_lat))))) AS distance',
		// 	$distanceUnit, $lat, $lng, $lat
		// );

		// $subselect = clone $query;
		// $subselect->selectRaw(DB::raw($haversine));

		// Optimize the query, see details here:
		// http://www.plumislandmedia.net/mysql/haversine-mysql-nearest-loc/

		$latDistance      = $radius / $distanceUnit;
		$latNorthBoundary = $lat - $latDistance;
		$latSouthBoundary = $lat + $latDistance;
		// $subselect->whereRaw(sprintf("location_lat BETWEEN %f AND %f", $latNorthBoundary, $latSouthBoundary));

		$lngDistance     = $radius / ($distanceUnit * cos(deg2rad($lat)));
		$lngEastBoundary = $lng - $lngDistance;
		$lngWestBoundary = $lng + $lngDistance;
		// $subselect->whereRaw(sprintf("location_lng BETWEEN %f AND %f", $lngEastBoundary, $lngWestBoundary));

		// $query
		// 	->from(DB::raw('(' . $subselect->toSql() . ') as d'))
		// 	->where('distance', '<=', $radius);

		$query->whereRaw(sprintf("location_lat BETWEEN %f AND %f", $latNorthBoundary, $latSouthBoundary));
		$query->whereRaw(sprintf("location_lng BETWEEN %f AND %f", $lngEastBoundary, $lngWestBoundary));

		// if opportunity
		if($this instanceof Opportunity)
			$query->orWhere('virtual', 1);

		return $query;
	}

	public function scopeWithDistance($query, $lat, $lng, $radius = 50, $unit = 'M')
	{
		$distanceUnit = ($unit == 'M') ? 69 : 111.045;

		$haversine = sprintf('*, (%f * DEGREES(ACOS(COS(RADIANS(%f)) * COS(RADIANS(location_lat)) * COS(RADIANS(%f - location_lng)) + SIN(RADIANS(%f)) * SIN(RADIANS(location_lat))))) AS distance',
			$distanceUnit, $lat, $lng, $lat
		);

		$subselect = clone $query;
		$subselect->selectRaw(DB::raw($haversine));

		// Optimize the query, see details here:
		// http://www.plumislandmedia.net/mysql/haversine-mysql-nearest-loc/

		$latDistance      = $radius / $distanceUnit;
		$latNorthBoundary = $lat - $latDistance;
		$latSouthBoundary = $lat + $latDistance;
		$subselect->whereRaw(sprintf("location_lat BETWEEN %f AND %f", $latNorthBoundary, $latSouthBoundary));

		$lngDistance     = $radius / ($distanceUnit * cos(deg2rad($lat)));
		$lngEastBoundary = $lng - $lngDistance;
		$lngWestBoundary = $lng + $lngDistance;
		$subselect->whereRaw(sprintf("location_lng BETWEEN %f AND %f", $lngEastBoundary, $lngWestBoundary));

		$query
			->from(DB::raw('(' . $subselect->toSql() . ') as d'))
			->where('distance', '<=', $radius);
	}

	/**
	 * @param $units
	 */
	private function distanceUnit($units = 'K')
	{
		if ($units == 'K') {
			return static::DISTANCE_UNIT_KILOMETERS;
		} elseif ($units == 'M') {
			return static::DISTANCE_UNIT_MILES;
		} else {
			throw new Exception("Unknown distance unit measure '$units'.");
		}
	}
}