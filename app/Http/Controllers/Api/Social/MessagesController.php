<?php

namespace App\Http\Controllers\Api\Social;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

class MessagesController extends ApiController
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->volunteer = auth()->user()->volunteer;
            return $next($request);
        });
    }

    public function index(Request $request) {
    	return response()->json(array('items' => array(
    		array('foo1' => 'bar1', 'fruit' => 'orange'),
    		array('foo2' => 'bar2', 'fruit' => 'apple'),
    		array('foo3' => 'bar3', 'fruit' => 'pear'),
    	)));
    }
}