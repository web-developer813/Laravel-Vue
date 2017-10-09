<?php

namespace App\Mail\Forprofits;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Donation;
use App\Forprofit;

class DonationRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $donation;
    public $forprofit;
    public $nonprofit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Donation $donation, Forprofit $forprofit)
    {
        $this->donation = $donation;
        $this->forprofit = $forprofit;
        $this->nonprofit = $donation->nonprofit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('app.emails.forprofits.donation-request')
            ->to($this->forprofit->email)
            ->subject("You received a donation request from {$this->nonprofit->name}");
    }
}
