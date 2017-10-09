<?php

namespace App\Listeners;

use Intercom\IntercomClient;

class IntercomListeners
{

    protected $intercom;

    public function __construct()
    {
        $this->intercom = new IntercomClient(getenv('INTERCOM_ACCESS_TOKEN'), null);
    }
    
    # create event
    protected function create_event($array)
    {
        try
        {
            $this->intercom->events->create($array);
        }

        catch (\Exception $e)
        {
            // dd($e);
        }
    }

    # update user
    protected function update_user($array)
    {
        try
        {
            $this->intercom->users->create($array);
        }

        catch (\Exception $e)
        {
            // dd($e);
        }
    }

    # volunteer created
    public function onVolunteerCreated($volunteer)
    {
        $user = $volunteer->user;

        $this->update_user([
            'user_id' => $volunteer->user_id,
            'name' => $volunteer->name,
            'email' => $user->email,
            'created_at' => $user->created_at->timestamp,
            'custom_attributes' => [
                'user_type' => 'volunteer',
                'applications' => 0,
                'points' => 0,
                'minutes' => 0,
            ]
        ]);
    }

    # nonprofit created
    public function onNonprofitCreated($nonprofit)
    {
        $this->update_user([
            'user_id' => 'N' . $nonprofit->user_id,
            'name' => $nonprofit->name,
            'email' => $nonprofit->email,
            'created_at' => $nonprofit->created_at->timestamp,
            'custom_attributes' => [
                'user_type' => 'nonprofit',
                'opportunities' => 0,
            ]
        ]);
    }

    # forprofit created
    public function onForprofitCreated($forprofit)
    {
        $this->update_user([
            'user_id' => 'F' . $forprofit->user_id,
            'name' => $forprofit->name,
            'email' => $forprofit->email,
            'created_at' => $forprofit->created_at->timestamp,
            'custom_attributes' => [
                'user_type' => 'business',
                'incentives' => 0,
            ]
        ]);
    }

    # application created
    public function onApplicationCreated($application)
    {
        $volunteer = $application->volunteer;

        $this->create_event([
            'event_name' => 'application-created',
            'user_id' => $volunteer->user_id,
            'created_at' => $application->created_at->timestamp,
            'metadata' => [
                'nonprofit_id' => $application->nonprofit_id,
                'opportunity_id' => $application->opportunity_id,
                'volunteer_message' => $application->volunteer_message
            ]
        ]);

        $this->update_user([
            'user_id' => $volunteer->user_id,
            'name' => $volunteer->name,
            'email' => $volunteer->user->email,
            'custom_attributes' => [
                'applications' => $volunteer->applications()->count()
            ]
        ]);
    }

    # opportunity created
    public function onOpportunityCreated($opportunity)
    {
        $nonprofit = $opportunity->nonprofit;

        $this->create_event([
            'event_name' => 'opportunity-created',
            'user_id' => 'N' . $nonprofit->id,
            'created_at' => $opportunity->created_at->timestamp,
            'metadata' => [
                'title' => $opportunity->title,
                'flexible' => ($opportunity->flexible) ? true : false,
                'virtual' => ($opportunity->virtual) ? true : false,
            ]
        ]);

        $this->update_user([
            'user_id' => 'N' . $nonprofit->id,
            'name' => $nonprofit->name,
            'email' => $nonprofit->email,
            'custom_attributes' => [
                'opportunities' => $nonprofit->opportunities()->count(),
            ]
        ]);
    }

    # incentive created
    public function onIncentiveCreated($incentive)
    {
        $forprofit = $incentive->forprofit;

        $this->create_event([
            'event_name' => 'incentive-created',
            'user_id' => 'F' . $forprofit->id,
            'created_at' => $incentive->created_at->timestamp,
            'metadata' => [
                'title' => $incentive->title,
                'price' => $incentive->price,
            ]
        ]);

        $this->update_user([
            'user_id' => 'F' . $forprofit->id,
            'name' => $forprofit->name,
            'email' => $forprofit->email,
            'custom_attributes' => [
                'incentives' => $forprofit->incentives()->count(),
            ]
        ]);
    }

}