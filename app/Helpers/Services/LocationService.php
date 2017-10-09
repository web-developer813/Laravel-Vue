<?php

namespace App\Helpers\Services;

use Illuminate\Http\Exception\HttpResponseException;
use App\Exceptions\CustomValidationException;

class LocationService
{
    # get full address
    public static function getFullAddress($location, $old_location = null, $strict = true)
    {
        $location = trim($location) ?: null;

        // return if location unchanged
        if ($location == $old_location) {
            return [];
        }

        if (!$location) {
            return [
                'location_lat' => null,
                'location_lng' => null,
                'location_address' => null,
                'location_city' => null,
                'location_state' => null,
                'location_country' => null,
                'location_postal_code' => null,
            ];
        }

        // geocode location
        try {
            $g = app('geocoder')->geocode($location)->all();
            if (is_array($g)) {
                $g = $g[0];
            } else {
                $g = $g->first();
            }
        } catch (NoResultException $e) {
            throw new CustomValidationException(['location' => 'Please enter a valid address']);
        }

        $data = array(
            'location_lat' => $g->getLatitude(),
            'location_lng' => $g->getLongitude(),
            'location_address' => $g->getStreetNumber() . $g->getStreetName(),
            'location_city' => $g->getLocality(),
            'location_state' => $g->getSubLocality(),
            'location_country' => $g->getCountry(),
            'location_postal_code' => $g->getPostalCode(),
        );

        // not a full address
        if ($strict && count(array_filter($data)) !== 7) {
            throw new CustomValidationException(['location' => 'Please enter a valid address']);
        }

        // no latitude or longitude
        if (!$strict && (!$data['location_lat'] || !$data['location_lng'])) {
            throw new CustomValidationException(['location' => 'Please enter a valid address']);
        }

        return $data;
    }

    # get coordinates
    public static function getCoordinates($location, $old_location = null)
    {
        $location = trim($location) ?: null;

        // return if location unchanged
        if ($location == $old_location) {
            return [];
        }

        if (!$location) {
            return [
                'location_lat' => null,
                'location_lng' => null,
            ];
        }

        // geocode location
        try {
            $g = app('geocoder')->geocode($location)->get();
            $g = $g->first();
            $data = array('latitude' => $g->getLatitude() , 'longitude' => $g->getLongitude());
        } catch (NoResultException $e) {
            throw new CustomValidationException(['location' => 'Please enter a valid location']);
        }

        // not a valid location
        if (count(array_filter($data)) !== 2) {
            throw new CustomValidationException(['location' => 'Please enter a valid location']);
        }

        return [
            'location_lat' => $data['latitude'],
            'location_lng' => $data['longitude'],
        ];
    }
}