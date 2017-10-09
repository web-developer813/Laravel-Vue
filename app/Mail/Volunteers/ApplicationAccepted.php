<?php

namespace App\Mail\Volunteers;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Application;
use App\Volunteer;

class ApplicationAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $volunteer;
    public $opportunity;
    public $nonprofit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Application $application, Volunteer $volunteer)
    {
        $this->application = $application;
        $this->volunteer = $volunteer;
        $this->opportunity = $application->opportunity;
        $this->nonprofit = $application->nonprofit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('app.emails.volunteers.application-accepted')
            ->to($this->volunteer->email)
            ->subject("Your application to {$this->opportunity->title} has been accepted");
    }
}
