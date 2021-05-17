<?php

namespace App\Console;

use App\Jobs\FbReporting\CampaignOptimizerJob;
use App\Jobs\FbReporting\LoadBMFacebookPagesJob;
use App\Jobs\FbReporting\ProcessScheduledFacebookPagePostsJob;
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
        // $schedule->command('inspire')->hourly();
        $schedule->job(new LoadBMFacebookPagesJob)->twiceDaily();
        $schedule->job(new CampaignOptimizerJob)->dailyAt('16:00:00');
        $schedule->job(new ProcessScheduledFacebookPagePostsJob)->everyTwoMinutes()->withoutOverlapping();
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
