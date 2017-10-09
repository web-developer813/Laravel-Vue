<?php

namespace App\Http\Controllers\Api\Volunteers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use App\Opportunity;
use App\Volunteer;
use App\Post;
use GetStream\StreamLaravel\Enrich;

class NewsfeedController extends ApiController
{
	protected $volunteer;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
            $this->volunteer = auth()->user()->volunteer;
            return $next($request);
        });
	}

	# index
	public function index(Request $request)
	{
		$user_id = $this->volunteer->id;
		$user = $this->volunteer;

		// Get your timeline:
        $feed = \FeedManager::getNewsFeeds($user_id)['timeline_aggregated'];

        $token = $feed->getReadonlyToken();

        // Setup Pagination
        $options = array();

		if ($request->id_lt) {
			$options['id_lt'] = $request->id_lt;
		}

		$groups = $feed->getActivities(0,5,$options)['results'];

        // Get your timeline activities from Stream:
        

        // Enrich Activities
        $enricher = new Enrich(array('actor', 'object'));
        $groups = $enricher->enrichAggregatedActivities($groups);
        
        $total = count($groups);

        // Move aggregated activities into a single array
        $activities = array();
        foreach ($groups as $group) {
        	foreach ($group['activities'] as $activity) {
        		//$activity['followable_id'] = $user->follows->where('followable_id','=',$activity['actor']['id'])->first()->id;
        		$activities[] = $activity;
        	}
        }

        // Get the last item id to generate the pagination url
        $nextPageUrl = '';

        $lastActivity = end($groups);
        
        if ($total === 5) {
        	$nextPageUrl = $request->route()->uri . '/?id_lt=' . $lastActivity['id'];
        }
        
		$items = [];
		
		foreach ($activities as $activity) {
			$time = new Carbon($activity['time']);
			$activity['following'] = $this->volunteer->following->where('followable_id','=',$activity['actor']['id'])->first();
			$type = explode(':',$activity['foreign_id']);
			if (method_exists($type[0], 'likes')) {
				$model = new $type[0];
				$object = $model::find($activity['object']->id);
				$activity['total_likes'] = $object->likes->count();
				$activity['like'] = $object->likedByMe();
			}
			$activity['slug'] = lcfirst(explode('\\', $type[0])[1]);
			$activity['type'] = $type[0];
			$activity['formated_time'] = $time->diffForHumans();
			$items[] = $activity;
		}

		usort($activities, function ($a, $b) {
			return strcmp($a['time'], $b['time']);
		});

		$rectotal = count($activities);

		return response()->json([
			'items' => $items, // should be $items
			'nextPageUrl' => $nextPageUrl,
			'meta' => array(
				'total' => $rectotal,
				'token' => $token,
				'user_id' => $user_id, 
				'api_key' => env('GETSTREAM_API_KEY'), 
				'app_id' => env('GETSTREAM_API_APPID')
			),
		]);
	}

	# timeline
	public function timeline(Request $request)
	{

		// Get the feed of the specified user:
        $feed = FeedManager::getUserFeed($request->user()->id);

        $enricher = new Enrich;

        // Get your timeline activities from Stream:
        $activities = $feed->getActivities(0,25)['results'];
        $activities = $enricher->enrichActivities($activities);
		
		$items = [];
		

		return response()->json([
			'items' => $items,
		]);
	}

}
