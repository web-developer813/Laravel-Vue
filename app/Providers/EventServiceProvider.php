<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Upload;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\OpportunityPublished' => [
        //     'App\Listeners\GetStream\AddToFeed',
        // ],
        // 'App\Events\OpportunityUnpublished' => [
        //     'App\Listeners\GetStream\RemoveFromFeed'
        // ],
    ];

     /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        // 'App\Listeners\IntercomListeners',
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
