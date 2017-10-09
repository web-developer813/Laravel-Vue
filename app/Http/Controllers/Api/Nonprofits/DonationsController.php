<?php

namespace App\Http\Controllers\Api\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Nonprofits\StorePointsDonationRequest;
use App\Http\Controllers\ApiController;
use App\Forprofit;
use App\Donation;
use DB;

class DonationsController extends ApiController
{
    protected $nonprofit;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->nonprofit = config()->get('authNonprofit');
            return $next($request);
        });
    }

    # index
    public function index(Request $request, $nonprofit_id)
    {
        $query = $this->nonprofit->donations();

        // status
        switch ($request->status) {
            case 'fulfilled':
                $query->fulfilled();
                break;

            case 'pending':
                $query->pending();
                break;
        }

        // search
        if ($request->search) {
            $query->search($request->search);
        }

        // with relationships
        $query->with('donater');

        // order
        $query->ordered();

        // pagination
        $donations = $query->paginate(20);
        $donations->appends($request->except('page'));

        // items
        $items = [];
        foreach ($donations as $donation) {
            $items[] = [
                'donation' => $donation->toArray(),
                'donater' => $donation->donater->toArray(),
            ];
        }

        return response()->json([
            'items' => $items,
            'nextPageUrl' => nextPageUrl($donations->nextPageUrl()),
        ]);
    }

    # store
    public function store(StorePointsDonationRequest $request, $nonprofit_id, $forprofit_id)
    {
        dd($request->all());
        $forprofit = Forprofit::verified()->findOrFail($forprofit_id);

        // check if that nonprofit has enough monthly points remaining
        if ($forprofit->monthly_points_remaining < $request->points) {
            return response()->json(['message' => "This donation request exceeds the remaining monthly donation budget of {$forprofit->name}"], 422);
        }

        $donation = DB::transaction(function () use ($forprofit, $request) {
            $donation = Donation::create([
                    'donater_type' => get_class($forprofit),
                    'donater_id' => $forprofit->id,
                    'donater_name' => $forprofit->name,
                    'nonprofit_id' => $this->nonprofit->id,
                    'points' => $request->points
                ]);
            return $donation;
        });

        flash("Your donation request to {$forprofit->name} has been sent", 'success');

        return response()->json([
            'redirect_url' => route('nonprofits.donations.index', $this->nonprofit->id),
            'message' => "Your donation request to {$forprofit->name} has been sent"
        ]);
    }
}
