<?php

namespace App\Mail\Volunteers;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\IncentivePurchase;
use App\Volunteer;

class IncentivePurchased extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;
    public $volunteer;
    public $forprofit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(IncentivePurchase $purchase)
    {
        $this->purchase = $purchase;
        $this->volunteer = $purchase->volunteer;
        $this->forprofit = $purchase->forprofit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('app.emails.volunteers.incentive-purchased')
            ->to($this->volunteer->email)
            ->subject("Your Tecdonor coupon [{$this->purchase->title}]");
    }
}
