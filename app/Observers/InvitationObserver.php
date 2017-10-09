<?php

namespace App\Observers;

use App\Invitation;
use App\Mail\Forprofits\InvitationToEmployees;
use App\Mail\Nonprofits\InvitationToVolunteers;
use Mail;

class InvitationObserver
{
    // invitation hash
    public function created(Invitation $invitation)
    {
        // save hash
        $invitation->update(['hash' => encode_hash($invitation->id, 'invitation')]);
    }
}