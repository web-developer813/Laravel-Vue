<?php

namespace App\Http\Controllers\Nonprofits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DonationsController extends Controller
{
    # index
    public function index()
    {
        return view('nonprofits.donations.index');
    }
    public function store(Request $request)
    {
        return view('nonprofits.donations.index');
    }
}
