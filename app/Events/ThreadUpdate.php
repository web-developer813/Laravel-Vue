<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Thread;
use App\Volunteer;

class ThreadUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $thread;
    public $volunteer;
    public $notifyIds;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, Thread $thread, Volunteer $volunteer,$notifyIds)
    {
        $this->message = $message;
        $this->thread = $thread;
        $this->volunteer = $volunteer;
        $this->notifyIds = $notifyIds;
    }

    public function broadcastWith() {

        return [
            'id' => $this->thread->id,
            'excerpt' => $this->message->body,
            'unread_messages' => 1,
            'user_id' => $this->thread->creator()->id,
            'updated_at' => $this->thread->updated_at->toW3cString(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $notifications = [];

        foreach($this->notifyIds as $id) {
            $notifications[] = new PrivateChannel('user-'.$id);
        }
        return $notifications;
    }
}
