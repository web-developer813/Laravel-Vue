<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->uploadPath = public_path().'/uploads/';
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
}
