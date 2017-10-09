<?php

namespace App\Mail\Forprofits;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Invitation;
use App\Forprofit;

class InvitationToEmployees extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $forprofit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation, Forprofit $forprofit)
    {
        $this->invitation = $invitation;
        $this->forprofit = $forprofit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('app.emails.forprofits.invitation-to-employees')
            ->to($this->invitation->email)
            ->subject("You're invited join {$this->forprofit->name} on Tecdonor");
    }
}
