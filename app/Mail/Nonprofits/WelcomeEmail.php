<?php

namespace App\Mail\Nonprofits;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Nonprofit;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $nonprofit;

    public function __construct(Nonprofit $nonprofit)
    {
        $this->nonprofit = $nonprofit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('app.emails.nonprofits.welcome')
            ->to($this->nonprofit->email)
            ->subject("Welcome to Tecdonor {$this->nonprofit->name}!");
    }
}
