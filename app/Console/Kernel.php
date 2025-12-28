<?php

namespace App\Console;

use App\Jobs\CancelExpiredReservations;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule the `CancelExpiredReservations` job to run every hour
        // This ensures expired reservations are automatically cancelled in a timely manner
        // Urgent: 5 hours, Standard: 48 hours (2 days), Flexible: 72 hours (3 days)
      $schedule->job(new CancelExpiredReservations())->everyMinute();
      
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Load commands from the specified path
        $this->load(__DIR__.'/Commands');

        // Include any other routes for console commands
        require base_path('routes/console.php');
    }
}
