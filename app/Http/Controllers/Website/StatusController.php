<?php

namespace App\Http\Controllers\Website;

use App\Volunteer;
use App\Events\UserOnline;
use App\Events\UserOffline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    public function post() {
		$app_secret = env('PUSHER_SECRET');
		$app_key = $_SERVER['HTTP_X_PUSHER_KEY'];
		$webhook_signature = $_SERVER ['HTTP_X_PUSHER_SIGNATURE'];
		$body = file_get_contents('php://input');
		$expected_signature = hash_hmac( 'sha256', $body, $app_secret, false );

		if($webhook_signature == $expected_signature) {
			// decode as associative array
			$payload = json_decode( $body, true );
			foreach($payload['events'] as &$event) {
				if (substr($event['channel'],0,13) === 'private-user-' && $event['name'] == 'channel_occupied' || substr($event['channel'],0,13) === 'private-user-' && $event['name'] == 'channel_vacated' ) {
					
					$id = (int) substr($event['channel'],13);

					$volunteer = Volunteer::find($id);
					$volunteer->status = $event['name'] === 'channel_occupied' ? true : false;
					$volunteer->save();

					if ($volunteer->status) {
						broadcast(new UserOnline($volunteer))->toOthers();
					} else {
						broadcast(new UserOffline($volunteer))->toOthers();
					}
				}
			}

			header("Status: 200 OK");
		} else {
			header("Status: 401 Not authenticated");
		}
    }
}
