<?php

namespace App\Observers;

use App\Opportunity;
use GetStream\Stream\Client;

class OpportunityObserver
{
    // add opportunity to respective feeds
    public function created(Opportunity $opportunity)
    {
        if ($opportunity->published) {
            $nonprofit = $opportunity->nonprofit;
            $categories = $opportunity->categories;

            $to = [];

            foreach ($categories as $category) {
                $to[] = 'categories:'.$category->id;
            }

            if (!$opportunity->virtual) {
                $to[] = 'cities:'.str_slug($opportunity->location_city, '-');
            }

            $nonprofitfeed = \FeedManager::getFeed('organizations', $nonprofit->id);

            $time = date_format(date_create($opportunity->created_at), \DateTime::ISO8601);

            $data = array(
                "actor" => "App\Nonprofit:".$nonprofit->id,
                "verb" => "add",
                "object" => "App\Opportunity:".$opportunity->id,
                "to" => $to,
                "foreign_id" => "App\Opportunity:".$opportunity->id,
                "time" => $time,
            );

            $created = $nonprofitfeed->addActivity($data);

            if (!empty($created['id'])) {
                $opportunity->foreign_key = $created['id'];
            }
        }
    }

    // remove opportunity from respective feeds
    public function deleting(Opportunity $opportunity)
    {
        $nonprofit = $opportunity->nonprofit;

        $nonprofitfeed = \FeedManager::getFeed('organizations', $nonprofit->id);

        $nonprofitfeed->removeActivity('App\Opportunity:'.$opportunity->id, true);

        // Apparently this isn't necessary
        // foreach($categories as $category) {
        //     $categoryfeed = \FeedManager::getFeed('categories',$category->id);
        //     $categoryfeed->removeActivity('App\Opportunity:'.$opportunity->id,true);
        // }
    }

    public function updating(Opportunity $opportunity)
    {
        $client = new Client(env('GETSTREAM_API_KEY'), env('GETSTREAM_API_SECRET'), 'v1.0', env('GETSTREAM_LOCATION'));
        $nonprofit = $opportunity->nonprofit;
        $categories = $opportunity->categories;
        $exisitingCategories = \App\Opportunity::find($opportunity->id)->categories->pluck('id')->toArray();

        //$exisitingCategories = $opportunity->getOriginal('categories');


        $nonprofitfeed = \FeedManager::getFeed('organizations', $nonprofit->id);

        $to = [];

        foreach ($categories as $category) {
            $to[] = 'categories:'.$category->id;
        }

        if (!$opportunity->virtual) {
            $to[] = 'cities:'.str_slug($opportunity->location_city, '-');
        }

        $time = date_format(date_create($opportunity->created_at), \DateTime::ISO8601);

        $data = array(
            "actor" => "App\Nonprofit:".$nonprofit->id,
            "verb" => "add",
            "object" => "App\Opportunity:".$opportunity->id,
            "to" => $to,
            "foreign_id" => "App\Opportunity:".$opportunity->id,
            "time" => $time,
        );

        if (!empty($opportunity->foreign_key) && $opportunity->published == 1) {
            // We already have created this in getstream
            $removeCategories = array_diff($exisitingCategories, $categories->pluck('id')->toArray());
            if (!empty($removeCategories)) {
                foreach ($removeCategories as $category) {
                    $categoryfeed = \FeedManager::getFeed('categories', $category);
                    $categoryfeed->removeActivity('App\Opportunity:'.$opportunity->id, true);
                }
            }
            // Now let's update the original Activbity
            $client->updateActivity($data);

            // Now add the activity to any new feeds that were added
            $addCategories = array_diff($categories->pluck('id')->all(), $exisitingCategories);
            if (!empty($addCategories)) {
                foreach ($addCategories as $category) {
                    $categoryfeed = \FeedManager::getFeed('categories', $category);
                    $categoryfeed->addActivity(array(
                        "actor" => "App\Nonprofit:".$nonprofit->id,
                        "verb" => "add",
                        "object" => "App\Opportunity:".$opportunity->id,
                        "foreign_id" => "App\Opportunity:".$opportunity->id,
                        "time" => $time,
                    ));
                }
            }
        } elseif ($opportunity->published == 1 && empty($opportunity->foreign_key)) {
            // Create the Activity now that it's published
            $created = $nonprofitfeed->addActivity($data);

            if (!empty($created['id'])) {
                $opportunity->foreign_key = $created['id'];
            }
        } elseif ($opportunity->published == 0 && !empty($opportunity->foreign_key)) {
            // This opportunity was unpublished and needs to be removed from get stream
            $nonprofitfeed->removeActivity('App\Opportunity:'.$opportunity->id, true);
        }
    }
}