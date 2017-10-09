<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
    protected $nonprofit;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
    }

    # edit
    public function edit()
    {
        return view('nonprofits.settings.billing');
    }

    # receipts
    public function receipts()
    {
        // no stripe id
        if (!$this->nonprofit->hasStripeId()) {
            return redirect()->route('nonprofits.settings.billing');
        }

        $invoices = $this->nonprofit->invoices();
        return view('nonprofits.settings.receipts', compact('invoices'));
    }

    # receipts download
    public function receipts_download(Request $request, $invoice_id)
    {
        return $this->nonprofit->downloadInvoice($invoice_id, [
            'vendor'  => 'Tecdonor',
            'product' => 'Tecdonor Nonprofit Organization Subscription',
        ]);
    }
}
