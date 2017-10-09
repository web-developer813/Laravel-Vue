<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Volunteers\DownloadIncentivePurchaseRequest;
use App\Http\Controllers\Controller;
use App\IncentivePurchase;
use PDF;

class IncentivePurchasesController extends Controller
{
	public function __construct() 
    {
        // Use middleware only on some functions
        $this->middleware('authForprofit');
    }
	# index
	public function index()
	{
		return view('volunteers.incentive-purchases.index');
	}

	# show
	public function show(DownloadIncentivePurchaseRequest $request, $purchase_id)
	{
		$purchase = IncentivePurchase::valid()->with('forprofit')->findOrFail($purchase_id);
		
		$pdf = PDF::loadView('volunteers.incentive-purchases.show', compact('purchase'));
		return $pdf->download("tecdonor-coupon-{$purchase->id}.pdf");
		// return view('volunteers.incentive-purchases.show', compact('purchase'));
	}
}
