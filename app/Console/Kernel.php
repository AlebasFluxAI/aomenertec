<?php

namespace App\Console;


use App\Console\Commands\V1\RecordDailyConsumption;
use App\Console\Commands\V1\RecordMonthlyConsumption;
use App\Console\Commands\V1\UpdateDailyConsumption;
use App\Console\Commands\V1\UpdateDataConsumption;
use App\Console\Commands\V1\UpdateMonthlyConsumption;
use App\Console\Commands\V1\UpdateTimestampDataConsumption;
use App\Jobs\V1\Enertec\SaveMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\AuxData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ConsumerCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        ////unpack data
        $schedule->command(UpdateTimestampDataConsumption::class)->everyTwoMinutes()->withoutOverlapping();
        $schedule->command(UpdateDataConsumption::class)->everyFiveMinutes()->withoutOverlapping();
        $schedule->command(SetTimestamp::class)->twiceDailyAt(1,13, 3);


        ////accumulated daily consumption
        $schedule->command(RecordDailyConsumption::class)->dailyAt('00:10');

        ////update accumulated daily consumption
        $schedule->command(UpdateDailyConsumption::class)->dailyAt('00:20');

        ////accumulated monthly consumption
        $schedule->command(RecordMonthlyConsumption::class)->dailyAt('00:25');

        ////update accumulated monthly consumption
        $schedule->command(UpdateMonthlyConsumption::class)->dailyAt('00:30');

        ///Generar facturacion....


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/V1/console.php');
    }
}
