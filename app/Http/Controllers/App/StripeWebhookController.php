<?php

namespace App\Http\Controllers\App;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

use Illuminate\Http\Request;
// use App\Helpers\app as AppHelper;
use App\Mail\PaymentDeclined;
use App\Volunteer;
use App\Nonprofit;
use App\Forprofit;
use Mail;

class StripeWebhookController extends CashierController
{
    /**
    * Handle a failed payment from a Stripe subscription.
    *
    * @param  array  $payload
    * @return \Symfony\Component\HttpFoundation\Response
    */
    protected function handleInvoicePaymentFailed(array $payload)
    {
        if (! $this->isInTestingEnvironment() && ! $this->eventExistsOnStripe($payload['id'])) {
            return;
        }

        // first failed attempt
        if ($payload['data']['object']['attempt_count'] == 1) {
            // email
            $billable = $this->getUserByStripeId($payload['data']['object']['customer']);
            Mail::send(new PaymentDeclined($billable));
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Get the billable entity instance by Stripe ID.
     *
     * @param  string  $stripeId
     * @return \Laravel\Cashier\Billable
     */
    protected function getUserByStripeId($stripeId)
    {
        // try volunteers
        $billable = Volunteer::whereStripeId($stripeId)->first();

        // try nonprofits
        if (!$billable) {
            $billable = Nonprofit::whereStripeId($stripeId)->first();
        }

        // try forprofits
        if (!$billable) {
            $forprofit = Nonprofit::whereStripeId($stripeId)->first();
        }

        return $billable;
    }

    /*
     * Store stripe pi for current mode
     */
    public function storeStripeId(Request $request)
    {
        $model = currentModel();

        $model->stripe_id= $request->get('stripe_id');
        $model->save();
        // dd($model);
        return redirect()->back();
    }
}
