<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
	# edit
	public function edit()
	{
		return view('volunteers.settings.billing');
	}

	# receipts
	public function receipts()
	{
		$volunteer = config()->get('authVolunteer');

		// no stripe id
		if (!$volunteer->hasStripeId())
			return redirect()->route('settings.billing');

		$invoices = $volunteer->invoices();
		return view('volunteers.settings.receipts', compact('invoices'));
	}

	# receipts download
	public function receipts_download(Request $request, $invoice_id)
	{
		return config()->get('authVolunteer')->downloadInvoice($invoice_id, [
	        'vendor'  => 'Tecdonor',
	        'product' => 'Tecdonor Volunteer Subscription',
	    ]);
	}
}
