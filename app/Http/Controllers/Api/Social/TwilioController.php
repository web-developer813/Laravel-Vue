<?php

namespace App\Http\Controllers\Api\Social;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\ApiController;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\IpMessagingGrant;
use Twilio\Rest\Client;
use App\Volunteer;

class TwilioController extends ApiController
{
    public function __construct() {
        $this->sid = config('services.twilio')['accountSid'];
        $this->token = config('services.twilio')['authToken'];
        $this->ipmSid = config('services.twilio')['ipmServiceSid'];
        $this->client = new Client($this->sid, $this->token);
    }

    public function generateToken(Request $request, AccessToken $accessToken, IpMessagingGrant $ipmGrant)
    {
        $appName = "TwilioChat";
        $deviceId = 'webapp'; // This is a temporary patch for now
        $volunteer = auth()->user()->volunteer;
        $identity = $volunteer->id;
        
        $endpointId = $appName . ":" . $identity . ":" . $deviceId;

        $accessToken->setIdentity($identity);

        $ipmGrant->setServiceSid($this->ipmSid);
        $ipmGrant->setEndpointId($endpointId);

        $accessToken->addGrant($ipmGrant);

        $response = array(
            'identity' => $identity,
            'token' => $accessToken->toJWT()
        );

        if(empty($volunteer->twilio_sid)) {
            $user = $this->client->chat->services($this->ipmSid)->users->create($identity);
            $volunteer->twilio_sid = $user->sid;
            $volunteer->save();
        }

        return response()->json($response);
    }

    public function openChannel(Request $request) {
        $user_id = auth()->user()->id;
        $list = explode(',',$request->users);
        $list[] = $user_id;
        asort($list); // We do this so that a new chat room isn't create with the same members if initiated by another party.
        $users = $list;
        // $users should contain an array of ID's
        if (count($users) >= 2) {
            $names = array();
            foreach($users as $user) {
                $volunteer = Volunteer::findOrFail($user);
                $names[] = $volunteer->name;
            }
            $friendlyName = 'Conversation Between ' . implode(', ', $names);
            $uniqueName = implode('::',$users);

            // Check if the channel exists already...if so retrieve it... otherwise create it
            $member = $this->client->chat
            ->services($this->ipmSid)
            ->users($user_id)
            ->fetch();

            $channels = $this->getChannels();

            $channel = null;
            foreach($channels as $ch) {
                if($ch->uniqueName == $uniqueName) {
                    $channel = $ch;
                }
            }

            if (empty($channel)) {
                $channel = $this->client->chat
                ->services($this->ipmSid)
                ->channels
                ->create(
                    array(
                        'friendlyName' => $friendlyName,
                        'uniqueName' => $uniqueName,
                        'type' => 'private',
                    )
                );
            }

            $members = $this->getMembers($channel->sid);

            if (count($members)) {
                foreach ($members as $member) {
                    if (!in_array($member->identity, $users)) {
                        $this->addMember($member->identity,$channel->sid);
                    }
                }
            } else {
                // There are no members...add everyone
                foreach ($users as $user) {
                    $this->addMember($user,$channel->sid);
                }
            }

            return response()->json(array('sid' => $channel->sid));
        }
    }

    public function createUser($volunteer) {
        $user = $client->chat->services($this->ipmSid)->users->create($volunteer->id);
        $volunteer->twilio_sid = $user->sid;
        $volunteer->save();
        return $user;
    }

    public function addMember($memberId,$channel) {
        $member = $this->client->chat
        ->services($this->ipmSid)
        ->channels($channel)
        ->members
        ->create($memberId);
        return $member;
    }

    public function getMembers($channel) {
        $members = $this->client->chat
        ->services($this->ipmSid)
        ->channels($channel)
        ->members
        ->read();

        return $members;
    }

    public function getChannels() {
        $channels = $this->client->chat
        ->services($this->ipmSid)
        ->channels
        ->read();
        return $channels;
    }

    public function getChannel($channelId) {
        $channel = $this->client->chat
        ->services($this->ipmSid)
        ->channels($channelId)
        ->fetch();
        return $channel;
    }

    private function getMemberChannels($userSid) {
        $url = 'https://chat.twilio.com/v2/Services/' . $this->ipmSid .'/Users/' . $userSid. '/Channels';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $this->sid . ":" . $this->token);
        $resp = curl_exec($ch);
        $resp = json_decode($resp, true);
        $channels = $resp['channels'];
        return $channels;
    }
}