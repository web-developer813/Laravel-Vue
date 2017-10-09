<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Cmgmyr\Messenger\Models\Message;
use App\Volunteer;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastWith() {

        $actor = Volunteer::withTrashed()->findOrFail($this->message->user_id);

        return [
            'id' => $this->message->id,
            'thread_id' => $this->message->thread_id,
            'user_id' => $this->message->user_id,
            'body' => $this->message->body,
            'actor' => $actor,
            'mine' => false,
            'created_at' => $this->message->created_at,
            'updated_at' => $this->message->updated_at,
            'deleted_at' => $this->message->deleted_at,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('thread-'. $this->message->thread_id);
    }
}
