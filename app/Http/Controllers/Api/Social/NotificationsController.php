<?php

namespace App\Http\Controllers\Api\Social;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\App;

class NotificationsController extends ApiController
{
    public function getIndex()
    {
        return view('app.components.notification');
    }

    public function postNotify(Request $request)
    {
        $notifyText = $request->notify_text;

        $pusher = App::make('pusher');
        $pusher->trigger( 'notifications',
            'new-notification', 
            array('text' => $notifyText),
            $request->socket_id
        );

        return response()->json(array('text' => $notifyText));
    }
}
