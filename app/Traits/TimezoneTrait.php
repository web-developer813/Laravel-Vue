<?php

namespace App\Traits;

use Auth;

trait TimezoneAccessor
{
    public function getMutatedTimestampValue($value)
    {

        $timezone = config('app.timezone');

        if (Auth::check() && Auth::user()->timezone) {
            $timezone = Auth::user()->timezone;
        }
        return Carbon::parse($value)
            ->timezone($timezone);
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->getMutatedTimestampValue($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return $this->getMutatedTimestampValue($value);
    }
}