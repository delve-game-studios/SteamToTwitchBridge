<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

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
        // $schedule->command('inspire')
        //          ->hourly();
        $logFile = sprintf('%s/logs/cron_%s.log', storage_path(), date('Ymd'));
        $tempFile = sprintf('%s/logs/cron_%s.temp', storage_path(), date('Ymd'));

        Storage::disk('local')->put('file.txt', 'Contents');
        $schedule->call(function () {
            $ServicesController = app()->make('\App\Http\Controllers\ServicesController');
            $ServicesController->callAction('serviceSequence', []);
        })
        ->everyMinute()
        ->evenInMaintenanceMode()
        ->sendOutputTo($tempFile);

        \File::append($logFile, \File::get($tempFile));
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
