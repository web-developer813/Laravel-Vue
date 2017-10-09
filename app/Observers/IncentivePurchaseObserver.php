<?php

namespace App\Observers;

use App\IncentivePurchase;
use App\Mail\Volunteers\IncentivePurchased;

use Mail;

class IncentivePurchaseObserver
{
	// send coupon by email
    public function created(IncentivePurchase $purchase)
    {
       Mail::send(new IncentivePurchased($purchase));
    }
}