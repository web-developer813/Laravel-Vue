<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Volunteer;
use App\Nonprofit;
use App\Forprofit;

class PaymentDeclined extends Mailable
{
    use Queueable, SerializesModels;

    public $billable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($billable)
    {
        $this->billable = $billable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // set per billable type
        if ($billable instanceof Volunteer)
            $this->view('app.emails.volunteers.payment-declined');

        elseif ($billable instanceof Nonprofit)
            $this->view('app.emails.nonprofits.payment-declined');

        elseif ($billable instanceof Forprofit)
            $this->view('app.emails.forprofits.payment-declined');
       
        return $this->to($this->billable->email)
            ->subject('Your payment to Tecdonor has been declined')
            ->bcc('support@tecdonor.com');
    }
}
