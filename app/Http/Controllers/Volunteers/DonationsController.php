<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Donation;

class DonationsController extends Controller
{
    # show
    public function show(Request $request, $donation_id)
    {
        $donation = Donation::with('nonprofit')->findOrFail($donation_id);

        return view('volunteers.donations.show', compact('donation'));
    }
}
