<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // Planifie la commande commissions:recalculate pour qu'elle s'exécute chaque mois
        // $schedule->command('commissions:recalculate')->everyMinute();
        $schedule->command('commissions:recalculate')->weeklyOn(4, "10:00");
  
        $schedule->command('python:run-predict-apporteurs-script')->weeklyOn(4, "11:00");
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
