<?php

namespace App\Console;

use App\Models\PLMSSetting;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $emailTime = PLMSSetting::where('parameter', 'blood_test_emails')->value('value');

        if ($emailTime) {
            $schedule->command('email:sendreminderdaily')
            ->dailyAt($emailTime);
        }
        // $schedule->command('inspire')->hourly();
        // $schedule->command('email:sendreminderhourly')->hourly();
        // $schedule->command('email:sendreminderdaily')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
