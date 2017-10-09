<?php

namespace App\Events;

use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Volunteer;

class NewThread implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $thread;
    public $message;
    public $volunteer;
    public $recipientIds;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Thread $thread, Message $message, Volunteer $volunteer, $recipientIds)
    {
        $this->thread = $thread;
        $this->message = $message;
        $this->volunteer = $volunteer;
        $this->recipientIds = $recipientIds;
    }

    public function broadcastWith()
    {
        $participants = $this->thread->recipients;
        $creator = $this->volunteer;
        $names = [];

        if (count($participants) > 1) {
            foreach($participants as $recipient) {
                $names[] =  $recipient['name'];
            }
            array_unshift($names, $creator->name);
            $title = implode(', ', $names);
        } else {
            $title = $creator->name;
        }
        
        $excerpt = str_limit($this->message->body,40);

        return [
            'id' => $this->thread->id,
            'title' => $title,
            'excerpt' => $excerpt,
            'unread_messages' => 1,
            'recipients' => $this->thread->recipients,
            'subject' => $this->thread->subject,
            'created_at' => $this->thread->created_at->toW3cString(),
            'updated_at' => $this->thread->updated_at->toW3cString(),
            'creator' => $this->volunteer,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $recipients = [];
        $ids = $this->recipientIds;

        foreach($ids as $id) {
            $recipients[] = new PrivateChannel('user-'.$id);
        }
        return $recipients;
    }
}
