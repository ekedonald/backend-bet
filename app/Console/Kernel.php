<?php

namespace App\Console;

use App\Jobs\CreatePoolJob;
use App\Jobs\FetchPoolPrice;
use App\Jobs\SettleBetsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new CreatePoolJob)->everyFiveMinutes();
        $schedule->job(new SettleBetsJob)->everyThirtySeconds();
        $schedule->job(new FetchPoolPrice)->everyTenSeconds();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
