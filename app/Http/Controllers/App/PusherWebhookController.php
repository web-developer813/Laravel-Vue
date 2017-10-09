<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Volunteer;

class PusherWebhookController extends Controller
{
	public function post(Request $request) {
		// environmental variable must be set
		$app_secret = env('PUSHER_APP_SECRET');

		$app_key = $_SERVER['HTTP_X_PUSHER_KEY'];
		$webhook_signature = $_SERVER ['HTTP_X_PUSHER_SIGNATURE'];

		$body = $request->getContent();

		$expected_signature = hash_hmac( 'sha256', $body, $app_secret, false );

		if($webhook_signature == $expected_signature) {
			// decode as associative array
			$payload = json_decode( $body, true );
			foreach($payload['events'] as &$event) {
				if ($event['event'] == 'member_removed' && !empty($event['user_id'])) {
					$volunteer = Volunteer::findOrFail($event['user_id']);
					$volunteer->status = false;
					$volunteer->save();
				}
				if ($event['event'] == 'member_added' && !empty($event['user_id'])) {
					$volunteer = Volunteer::findOrFail($event['user_id']);
					$volunteer->status = true;
					$volunteer->save();
				}
			}

			header("Status: 200 OK");
		}
		else {
			header("Status: 401 Not authenticated");
		}
	}
}
