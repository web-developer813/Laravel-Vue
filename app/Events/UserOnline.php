<?php

namespace App\Events;

use App\Volunteer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserOnline implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $volunteer;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'id' => $this->volunteer->id,
    //         'status' => true,
    //     ];
    // }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('status');
    }
}
