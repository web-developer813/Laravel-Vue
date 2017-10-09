<?php

namespace App\Mail\Volunteers;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $volunteer;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->volunteer = $user->volunteer;
    }

    public function build()
    {
        return $this->view('app.emails.volunteers.welcome')
            ->to($this->user->email)
            ->subject('Welcome to Tecdonor! Here’s what’s next...');
    }
}
