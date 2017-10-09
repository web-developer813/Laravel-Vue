<?php

namespace App\Http\Controllers\Api\Social;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Volunteer;
use App\Friendships\Models\FriendshipMessage;

class ConnectionsController extends ApiController
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->volunteer = auth()->user()->volunteer;
            return $next($request);
        });
    }

    public function index(Request $request) {
    	$method = $request->method;
    	$perPage = $request->perPage ? $request->perPage : 20;
        $params = $request->params ? $request->parms : null;
        $q = $request->q ? $request->q : null;

    	$success = false;
    	$message = 'Sorry, we had an issue completing this request. Please try again in a moment.';
    	$result = '';
    	$total = 0;
    	$nextPageUrl = '';

    	switch ($method) {
    		case 'getAllFriendships':
    			$result = $this->volunteer->getAllFriendships();
    			$total = count($result);
    			break;
    		case 'getPendingFriendships':
    			$result = $this->volunteer->getPendingFriendships();
    			$total = count($result);
    			break;
    		case 'getAcceptedFriendships':
    			$result = $this->volunteer->getAcceptedFriendships();
    			$total = count($result);
    			break;
    		case 'getDeniedFriendships':
    			$result = $this->volunteer->getDeniedFriendships();
    			$total = count($result);
    			break;
    		case 'getBlockedFriendships':
    			$result = $this->volunteer->getBlockedFriendships();
    			$total = count($result);
    			break;
    		case 'getFriendRequests':
    			$result = $this->volunteer->getFriendRequests();
    			$items = [];
    			foreach ($result as $friend) {
    				$item = Volunteer::findOrFail($friend->sender_id)->toArray();
                    $item['message'] = $friend->message ? $friend->message->message : 'No message included...';
                    $item['message_excerpt'] = str_limit($item['message'],45);
    				$item['requested_at'] = $friend->created_at;
    				$item['requested_at_human'] = $friend->created_at->diffForHumans();
                    $item['accepted'] = false;
                    $item['denied'] = false;
    				$items[] = $item;
    			}
    			$result = array_reverse($items);
    			$total = count($result);
    			break;
    		case 'getFriendsCount':
    			$result = $this->volunteer->getFriendsCount();
    			break;
    		case 'getFriendsOfFriends':
    			$result = $this->volunteer->getFriendsOfFriends($perPage);
    			$total = count($result);
    			break;
    		case 'getMutualFriends':
    			$result = $this->volunteer->getMutualFriends($target,$perPage);
    			$total = count($result);
    			break;
            case 'getFriendsWhere':
                if(!empty($q)) {
                    $where = ['name','LIKE', '%' . $q . '%'];
                    $result = $this->volunteer->getFriendsWhere($perPage,$where);
                    $total = count($result);
                }
                break;
            case 'getOnlineFriends':
                $result = $this->volunteer->getOnlineFriends($perPage);
                $total = count($result);
                break;
    		case 'getFriends':
    		default:
    			$result = $this->volunteer->getFriends($perPage);
    			$total = count($result);
    			break;
    	}

    	return response()->json(array(
    		'success' => !empty($result),
    		'object' => $result, 
    		'meta' => [
    			'total' => $total,
    		],
    		'nextPageUrl' => $nextPageUrl,
    	));
    }

    public function store(Request $request) {
    	$method = $request->method;
    	$target_id = $request->target_id;
    	$perPage = $request->perPage ? $request->perPage : 20;

    	$target = Volunteer::findOrFail($target_id);

    	$success = false;
    	$message = 'Sorry, we had an issue completing this request. Please try again in a moment.';
    	$result = '';
    	$total = 0;
    	$nextPageUrl = '';

    	switch ($method) {
    		case 'befriend':
    			$result = $this->volunteer->befriend($target);
                if (!empty($request->message)) {
                    $msg = new FriendshipMessage();
                    $msg->friendship_id = $result['id'];
                    $msg->message = $request->message;
                    $saved = $msg->save();
                    $result['message'] = $saved;
                }
    			break;
    		case 'unfriend':
    			$result = $this->volunteer->unfriend($target);
    			break;
    		case 'acceptFriendRequest':
    			$result = $this->volunteer->acceptFriendRequest($target);
    			break;
    		case 'denyFriendRequest':
    			$result = $this->volunteer->denyFriendRequest($target);
    			break;
    		case 'blockFriend':
    			$result = $this->volunteer->blockFriend($target);
    			break;
    		case 'unblockFriend':
    			$result = $this->volunteer->unblockFriend($target);
    			break;
    		case 'isFriendWith':
    			$result = $this->volunteer->isFriendWith($target);
    			break;
    		case 'hasFriendRequestFrom':
    			$result = $this->volunteer->hasFriendRequestFrom($target);
    			break;
    		case 'hasSentFriendRequestTo':
    			$result = $this->volunteer->hasSentFriendRequestTo($target);
    			break;
    		case 'hasBlocked':
    			$result = $this->volunteer->hasBlocked($target);
    			break;
    		case 'isBlockedBy':
    			$result = $this->volunteer->isBlockedBy($target);
    			break;
    		case 'getFriendship':
    			$result = $this->volunteer->getFriendship($target);
    			break;
    		case 'getMutualFriendsCount':
    			$result = $this->volunteer->getMutualFriendsCount($target);
    			break;
    		case 'getFriends':
    		default:
    			$result = $this->volunteer->getFriends($perPage);
    			$total = count($result);
    			break;
    	}

    	return response()->json(array(
    		'success' => !empty($result),
    		'object' => $result, 
    		'meta' => [
    			'total' => $total,
    		],
    		'nextPageUrl' => $nextPageUrl,
    	));
    }
}
