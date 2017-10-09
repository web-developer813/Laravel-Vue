<?php

namespace App\Observers;

use App\Like;
use GetStream\Stream\Client;

class LikeObserver
{
    // add opportunity to respective feeds
    public function created(Like $like)
    {
        $object_type = $like->likable_type;
        $object_id = $like->likable_id;

        $object = $object_type::findOrFail($object_id);

        switch ($object_type) {
            case 'App\Opportunity':
                $slug  = 'notificationorganizations';
                $owner = $object->nonprofit;
                break;
            case 'App\Incentive':
                $slug = 'notificationbusinesses';
                $owner = $object->forprofit;
                break;
            case 'App\Post':
            default:
                $slug = 'notification';
                $owner = $object->volunteer;
                break;
        }
        // Need to create a notification feed for nonprofits and businesses and then check for 

        $feed = \FeedManager::getFeed($slug,$owner->id);
        if (!empty($feed)) {
            $time = date_format(date_create($like->created_at),\DateTime::ISO8601);
            $data = [
                'actor' => 'App\Volunteer:'.$like->user_id,
                'verb' => 'like',
                'object' => $object_type.':'.$object_id,
                'foreign_id' => 'App\Like:'.$like->id,
                'time' => $time,
            ];

            $posted = $feed->addActivity($data);
            if (!empty($posted['id'])) {
                $like->foreign_key = $posted['id'];
                $like->save();
            }
        }
    }

    // remove opportunity from respective feeds
    public function deleting(Like $like)
    {
        $object_type = $like->likable_type;
        $object_id = $like->likable_id;

        $object = $object_type::findOrFail($object_id);

        switch ($object_type) {
            case 'App\Opportunity':
                $slug  = 'notificationorganizations';
                $owner = $object->nonprofit;
                break;
            case 'App\Incentive':
                $slug = 'notificationbusinesses';
                $owner = $object->forprofit;
                break;
            case 'App\Post':
            default:
                $slug = 'notification';
                $owner = $object->volunteer;
                break;
        }
        
        $feed = \FeedManager::getFeed($slug,$owner->id);

        $feed->removeActivity('App\Like:'.$like->id,true);
    }
}