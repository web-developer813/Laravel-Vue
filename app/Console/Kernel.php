<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\DropAllTables::class,
        Commands\BackupDbToS3::class,
        Commands\SendInvitations::class,
        Commands\UpdateIntercom::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // database backups
        $schedule->command('db:backup')->twiceDaily(3,15);

        // send out invitations
        $schedule->command('invitations:send')->everyMinute()->withoutOverlapping();

        // $schedule->command('inspire')
        //          ->hourly();
    }
}
