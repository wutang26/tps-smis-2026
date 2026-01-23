<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


    protected $commands = [
        \App\Console\Commands\DailyBeats::class,
    ];
    protected function schedule(Schedule $schedule): void
    {
        // Add your scheduled tasks here.
        $schedule->command('inspire')->everyTwentySeconds();
        $schedule->command('app:daily-beats')->daily();	;
        $schedule->command('students:restore-beat')->daily();
        
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
    


}