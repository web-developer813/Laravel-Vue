<?php

namespace App\Http\Controllers\Forprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DonationsController extends Controller
{
    protected $forprofit;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->forprofit = config()->get('authForprofit');
            return $next($request);
        });
    }

    # index
    public function index()
    {
        return view('forprofits.donations.index');
    }

    #edit
    public function edit(Request $request, $forprofit_id, $donation_id)
    {
        $donation = $this->forprofit->donations()->with('nonprofit')->findOrFail($donation_id);
        return view('forprofits.donations.edit', compact('donation'));
    }
}
