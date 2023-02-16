<?php

namespace App\Console;

use App\Console\Commands\MqvReservationCommand;
use App\Console\Commands\MqvSeatCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command(MqvReservationCommand::class)
            ->dailyAt('12:00')
            ->description('MQV 자리 예약')
            ->runInBackground()
            ->WithoutOverlapping();

        $schedule->command(MqvSeatCommand::class)
            ->weeklyOn(1, '09:30')
            ->description('MQV 좌석 크롤링')
            ->runInBackground()
            ->WithoutOverlapping();
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
