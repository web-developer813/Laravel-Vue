<?php

namespace App\Providers;

use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();
        
        Broadcast::channel('user-{id}', function ($user,$id) {
            return (int) $user->id === (int) $id;
        });

        Broadcast::channel('thread-{id}', function($user,$id){
            return Thread::findOrFail($id)->hasParticipant($user->id);
        });

        Broadcast::channel('status', function($user) {
            return true;
        });

        //Broadcast::channel();
    }
}