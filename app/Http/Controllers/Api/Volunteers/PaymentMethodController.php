<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use App\Http\Requests\Volunteers\UpdatePaymentMethodRequest;
use App\Http\Controllers\ApiController;
use Exception;

class PaymentMethodController extends ApiController
{
	# update
	public function update(UpdatePaymentMethodRequest $request)
	{
		$volunteer = config()->get('authVolunteer');
		try
		{
			$volunteer->updateCard($request->stripeToken);
		}
		catch (Exception $e)
		{
			return response()->json([
				'error_message' => 'Your credit card could not be processed. Please make sure you entered the information correctly and that you have the funds available.',
				'name_on_card' => true,
				'card_number' => true,
				'card_exp_month' => true,
				'card_exp_year' => true,
				'card_cvc' => true
			], 422);
		}
		$volunteer->name_on_card = $request->name_on_card;
		$volunteer->save();

		flash('Your subscription has been updated!', 'success');
		return response()->json([
			'redirect_url' => route('settings.billing')
		]);
	}
}
