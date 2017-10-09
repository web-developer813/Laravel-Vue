<?php

namespace App\Mail\Forprofits;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Forprofit;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $forprofit;

    public function __construct(Forprofit $forprofit)
    {
        $this->forprofit = $forprofit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('app.emails.forprofits.welcome')
            ->to($this->forprofit->email)
            ->subject("Welcome to Tecdonor {$this->forprofit->name}!");
    }
}
