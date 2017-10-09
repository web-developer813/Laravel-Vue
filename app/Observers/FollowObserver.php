<?php

namespace App\Observers;

use App\Follow;
use GetStream\Stream\Client;

class FollowObserver
{
    // add opportunity to respective feeds
    public function created(Follow $follow)
    {
        $user_id = $follow->user_id;
        $object_type = $follow->followable_type;
        $object_id = $follow->followable_id;

        $feeds = \FeedManager::getNewsFeeds($user_id);

        switch ($object_type) {
            case 'App\Nonprofit':
                $targetfeed = 'organizations';
                $notification  = 'notificationorganizations';
                break;
            case 'App\Forprofit':
                $targetfeed = 'businesses';
                $notification = 'notificationbusinesses';
                break;
            case 'App\Volunteer':
            default:
                $targetfeed = 'user';
                $notification = 'notification';
                break;
        }

        if (!empty($targetfeed)) {
            foreach ($feeds as $feed) {
                $feed->followFeed($targetfeed,$object_id);
            }

        }

        $notificationfeed = \FeedManager::getFeed($notification,$object_id);
        if (!empty($notificationfeed)) {
            $time = date_format(date_create($follow->created_at),\DateTime::ISO8601);
            $data = [
                'actor' => 'App\Volunteer:'.$user_id,
                'verb' => 'follow',
                'object' => $object_type.':'.$object_id,
                'foreign_id' => 'App\Follow:'.$follow->id,
                'time' => $time,
            ];

            $notificationfeed->addActivity($data);

        }
    }

    // remove opportunity from respective feeds
    public function deleting(Follow $follow)
    {
        $object_type = $follow->followable_type;
        $object_id = $follow->followable_id;

        $feeds = \FeedManager::getNewsFeeds($follow->user_id);

        switch ($object_type) {
            case 'App\Nonprofit':
                $slug  = 'organizations';
                break;
            case 'App\Forprofit':
                $slug = 'businesses';
                break;
            case 'App\Volunteer':
            default:
                $slug = 'user';
                break;
        }
        
        foreach($feeds as $feed) {
            $feed->unfollow($slug,$object_id);
        }

    }
}