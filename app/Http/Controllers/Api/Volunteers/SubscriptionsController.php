<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use App\Http\Requests\Volunteers\StoreSubscriptionRequest;
use App\Http\Requests\Volunteers\UpdateSubscriptionRequest;
use App\Http\Controllers\Controller;
use Exception;

class SubscriptionsController extends Controller
{
	# store
	public function store(StoreSubscriptionRequest $request)
	{
		$volunteer = config()->get('authVolunteer');

		try
		{
			$volunteer->newSubscription('main', $request->plan_id)
				->create($request->stripeToken);
			// set trial ends
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

		flash('Your subscription has been upgraded!', 'success');
		return response()->json([
			'redirect_url' => route('settings.billing')
		]);
	}

	# update
	public function update(UpdateSubscriptionRequest $request)
	{
		$volunteer = config()->get('authVolunteer');
		$volunteer->subscription('main')->swap($request->plan_id);

		flash('Your subscription has been updated!', 'success');
		return response()->json([
			'redirect_url' => route('settings.billing')
		]);
	}

	# destroy
	public function destroy(Request $request)
	{
		$volunteer = config()->get('authVolunteer');
		$volunteer->subscription('main')->cancel();

		flash('Your subscription has been cancelled', 'success');
		return response()->json([
			'redirect_url' => route('settings.billing')
		]);
	}
}
