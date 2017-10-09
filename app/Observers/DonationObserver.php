<?php

namespace App\Observers;

use App\Donation;
use App\Notifications\Forprofits\DonationRequest;

class DonationObserver
{
	// remove points from volunteer
	// add points to nonprofit
    public function created(Donation $donation)
    {
        $donater = $donation->donater;

        if (is_a($donater, 'App\Volunteer'))
            $this->handleForVolunteer($donation, $donater);

        if (is_a($donater, 'App\Forprofit'))
            $this->handleForForprofit($donation, $donater);

    	// give points to nonprofit
        $nonprofit = $donation->nonprofit;
    	$nonprofit->points = $nonprofit->points + $donation->points;
    	$nonprofit->save();
    }

    // handle for volunteer
    protected function handleForVolunteer($donation, $volunteer)
    {
        // remove points from volunteer
        $volunteer->points = $volunteer->points - $donation->points;
        $volunteer->save();
    }

    // handle for forprofit
    protected function handleForForprofit($donation, $forprofit)
    {
        // notify
        $forprofit->notify(new DonationRequest($donation));
    }
}