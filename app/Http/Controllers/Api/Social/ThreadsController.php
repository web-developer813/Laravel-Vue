<?php

namespace App\Http\Controllers\Api\Social;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\ApiController;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use App\Volunteer;

class ThreadsController extends ApiController
{
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->volunteer = auth()->user()->volunteer;
            return $next($request);
        });
    }

    public function authorize($ability, $arguments = [])
    {

    }

    public function index(Request $request) {

    	$threads = Thread::forUser($this->volunteer->id)->latest('updated_at')->paginate(20);

    	$threads->appends($request->except('page'));

    	$items = [];
    	foreach($threads as $thread) {
            $creatorId = $thread->creator()->id;
            $creator = Volunteer::withTrashed()->findOrFail($creatorId);

    		$ids = explode('::',$thread->subject);
    		$recipients = [];
            $names = [];

            for ($i=0; $i < count($ids); $i++) { 
                if(intval($ids[$i]) !== $creatorId) {
                    $volunteer = Volunteer::withTrashed()->findOrFail($ids[$i]);
                    $recipients[] = $volunteer;
                    $names[] = $volunteer->name;
                }
            }

            if ($this->volunteer->id === $creatorId) {
                $title = implode(', ', $names);
            } elseif ($this->volunteer->id !== $creatorId && count($recipients) > 1) {
                array_unshift($names, $creator->name);
                $title = implode(', ', $names);
            } else {
                $title = $creator->name;
            }
    		
    		$latestMessage = $thread->messages()->latest()->first();
    		if ($latestMessage->user_id === $this->volunteer->id) {
    			$excerpt = "You: " . str_limit(html_entity_decode($latestMessage->body),35);
    		} else {
    			$excerpt = str_limit($latestMessage->body,40);
    		}

    		$items[] = array(
    			'id' => $thread->id,
    			'created_at' => $thread->created_at->toW3cString(),
    			'updated_at' => $thread->updated_at->toW3cString(),
                'creator' => $creator,
    			'unread_messages' => $thread->userUnreadMessagesCount($this->volunteer->id),
    			'excerpt' => $excerpt,
    			'title' => $title,
    			'recipients' => $recipients,
    		);
    	}

    	return response()->json(array(
    		'items' => $items,
    		'meta' => array(
    			'total' => count($items),
    			'nextPageUrl' => nextPageUrl($threads->nextPageUrl()),
    		),
    	));
    }

    public function getMessages($id) {
    	$thread = Thread::findOrFail($id);
    	if (!in_array($this->volunteer->id, $thread->participantsUserIds())) {
    		return response()->json(array('success' => false, 'message' => 'Not Authorized'));
    	}

    	$messages = $thread->messages()->paginate(20);

    	$items = [];

    	foreach($messages as $message) {
    		$message->mine = false;
    		if ($message->user_id === $this->volunteer->id) {
    			$message->mine = true;
    			$message->actor = $this->volunteer;
    		} else {
    			$message->actor = Volunteer::withTrashed()->findOrFail($message->user_id);
    		}
    		$items[] = $message;
    	}

        $ids = $thread->participantsUserIds();
        $to = [];
        foreach ($ids as $id) {
            $to[] = Volunteer::withTrashed()->findOrFail($id);
        }

        $thread->participants = $to;

        $thread->markAsRead($this->volunteer->id);

    	return response()->json(array(
    		'items' => $items,
            'thread' => $thread,
    		'meta' => array(
    			'total' => count($items),
    			'nextPageUrl' => nextPageUrl($messages->nextPageUrl()),
    		),
    	));
    }

    public function store(Request $request) {
        $success = false;
        $msg = 'There was an error completing this request. Please try again.';
        $object = null;

        $this->validate($request, [
            'recipients.*' => 'required',
            'recipientIds.*' => 'required|integer',
            'body' => 'required',
        ]);
        $recipientIds = $request->recipientIds;
        array_unshift($recipientIds,$this->volunteer->id);
        sort($recipientIds);

        $subject = implode('::',$recipientIds);

        // Check and see if a thread already exists
        if ($thread = Thread::getBySubject($subject)->first()) {
            return $this->update($request,$thread);
        }

        $thread = Thread::create(
            [
                'subject' => $subject,
            ]
        );

        $message = Message::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => $this->volunteer->id,
                'body'      => strip_tags($request->body),
            ]
        );

        Participant::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => $this->volunteer->id,
                'last_read' => new Carbon,
            ]
        );

        $thread->addParticipant($request->recipientIds);

        $thread->title = $request->title;
        $thread->excerpt = $request->excerpt;
        $thread->unread_messages = 1;
        $thread->recipients = $request->recipients;


        broadcast(new \App\Events\NewThread($thread,$message,$this->volunteer,$request->recipientIds));

        $thread->unread_messages = 0;

        if (!empty($thread) && !empty($message)) {
            $success = true;
            $msg = 'Successfully created new thread';
            $object = $thread->toArray();
            $object['created_at'] = $thread->created_at->toW3cString();
        }

        return response()->json(array('success' => $success, 'message' => $msg, 'object' => $object));
    }

    public function update(Request $request, $thread = null) {
    	// Check to see if participants have changed...if so you'll need to add recipients
        $success = false;
        $msg = 'There was an error completing this request. Please try again.';

        if (empty($thread)) {
            $this->validate($request, [
                'id' => 'required|integer',
                'recipients.*' => 'required',
                'recipientIds.*' => 'required|integer',
                'body' => 'required|string',
            ]);

            try {
                $thread = Thread::findOrFail($request->id);
            } catch (ModelNotFoundException $e) {
                Session::flash('error_message', 'The thread with ID: ' . $request->id . ' was not found.');
                return redirect('messages');
            }
        }

        
        // Activate all particpants is presumably for when people leave the thread but then it's updated later I don't think we'll use this though
        //$thread->activateAllParticipants();

        $message = Message::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => $this->volunteer->id,
                'body'      => e(strip_tags($request->body)),
            ]
        );

        $participant = Participant::firstOrCreate(
            [
                'thread_id' => $thread->id,
                'user_id'   => $this->volunteer->id,
            ]
        );
        $participant->last_read = new Carbon;
        $participant->save();

        $thread->addParticipant($request->recipientIds);

        $ids = $request->recipientIds;
        $notifyIds = [];
        array_unshift($ids, $thread->creator()->id);
        for ($i=0; $i < count($ids); $i++) { 
            if($ids[$i] !== $this->volunteer->id) {
                $notifyIds[] = $ids[$i];
            }
        }


        broadcast(new \App\Events\NewMessage($message))->toOthers();
        broadcast(new \App\Events\ThreadUpdate($message,$thread,$this->volunteer,$notifyIds))->toOthers();

        if(!empty($thread) && !empty($message)) {
            $success = true;
            $msg = 'Successfully created message';
        }

        return response()->json(array('success' => $success, 'message' => $msg));
    }

    public function markRead(Request $request) {
        $success = false;
        $msg = 'There was an error completing this request. Please try again.';

        $this->validate($request, [
            'thread_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $thread = Thread::findOrFail($request->thread_id);
        $success = $thread->markAsRead($request->user_id);

        if ($success) {
            $msg = 'Successfully updated read marker';
        }

        return response()->json(array('success' => $success, 'message' => $msg));

    }
}
