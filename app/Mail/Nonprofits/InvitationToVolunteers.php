<?php

namespace App\Mail\Nonprofits;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Invitation;
use App\Nonprofit;

class InvitationToVolunteers extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $nonprofit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation, Nonprofit $nonprofit)
    {
        $this->invitation = $invitation;
        $this->nonprofit = $nonprofit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('app.emails.nonprofits.invitation-to-volunteers')
            ->to($this->invitation->email)
            ->subject("You're' invited to join {$this->nonprofit->name} on Tecdonor");
    }
}
