<?php

namespace App\Observers;

use App\Hours;

class HoursObserver
{
    // add points to volunteer
    public function created(Hours $hours)
    {
        $volunteer = $hours->volunteer;
        // points
        $new_points = $volunteer->points + $hours->points;
        $volunteer->points = $new_points;
        // minutes
        $new_minutes = $volunteer->minutes + $hours->minutes;
        $volunteer->minutes = $new_minutes;
        $volunteer->save();
    }

    // substract points from volunteer
    public function deleted(Hours $hours)
    {
        $volunteer = $hours->volunteer;
        // points
        $new_points = $volunteer->points - $hours->points;
        $volunteer->points = $new_points;
        // minutes
        $new_minutes = $volunteer->minutes - $hours->minutes;
        $volunteer->minutes = $new_minutes;
        $volunteer->save();
    }
}