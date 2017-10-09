<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Invitation;
use Mail;

class SendInvitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invitations:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invitations';

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
        $invitations = Invitation::whereNotNull('hash')
            ->whereSent(0)
            ->limit(10)
            ->get();

        foreach($invitations as $invitation)
        {
            $invitation->send();
        }
    }
}
