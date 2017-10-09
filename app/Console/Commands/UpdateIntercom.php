<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intercom\IntercomClient;
use App\Volunteer;
use App\Nonprofit;
use App\Forprofit;

class UpdateIntercom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intercom:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all intercom user types';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // intercom
        $intercom = new IntercomClient(getenv('INTERCOM_ACCESS_TOKEN'), null);

        // volunteers
        $volunteers = Volunteer::all();

        foreach($volunteers as $volunteer)
        {
            $user = $volunteer->user;
            $intercom->users->create([
                'user_id' => $volunteer->user_id,
                'name' => $volunteer->name,
                'email' => $user->email,
                'created_at' => $user->created_at->timestamp,
                'custom_attributes' => [
                    'user_type' => 'volunteer',
                    'applications' => $volunteer->applications()->count(),
                    'points' => $volunteer->points,
                    'minutes' => $volunteer->minutes,
                ]
            ]);
        }

        // nonprofits
        $nonprofits = Nonprofit::all();

        foreach($nonprofits as $nonprofit)
        {
            $intercom->users->create([
                'user_id' => 'N' . $nonprofit->id,
                'name' => $nonprofit->name,
                'email' => $nonprofit->email,
                'created_at' => $nonprofit->created_at->timestamp,
                'custom_attributes' => [
                    'user_type' => 'nonprofit',
                    'opportunities' => $nonprofit->opportunities()->count(),
                ]
            ]);
        }

        // forprofits
        $forprofits = Forprofit::all();

        foreach($forprofits as $forprofit)
        {
            $intercom->users->create([
                'user_id' => 'F' . $forprofit->id,
                'name' => $forprofit->name,
                'email' => $forprofit->email,
                'created_at' => $forprofit->created_at->timestamp,
                'custom_attributes' => [
                    'user_type' => 'business',
                    'incentives' => $forprofit->incentives()->count(),
                ]
            ]);
        }
    }
}
