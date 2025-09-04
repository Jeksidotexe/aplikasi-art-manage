<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan perintah setiap hari pada jam 7 pagi;
        $schedule->command('app:kirim-email-jatuh-tempo')->dailyAt('07:00');

        // Jalankan perintah setiap hari pada jam 8 pagi
        $schedule->command('app:kirim-email-keterlambatan')->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
