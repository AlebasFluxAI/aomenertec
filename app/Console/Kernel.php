<?php

namespace App\Console;


use App\Console\Commands\V1\RecordDailyConsumption;
use App\Console\Commands\V1\RecordMonthlyConsumption;
use App\Console\Commands\V1\UpdateDailyConsumption;
use App\Console\Commands\V1\UpdateDataConsumption;
use App\Console\Commands\V1\UpdateMonthlyConsumption;
use App\Jobs\V1\Enertec\SaveMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\AuxData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        ////unpack data
        $schedule->command(UpdateDataConsumption::class)->everyThreeMinutes();

        ////accumulated daily consumption
        $schedule->command(RecordDailyConsumption::class)->dailyAt('00:03');

        ////update accumulated daily consumption
        $schedule->command(UpdateDailyConsumption::class)->dailyAt('00:06');

        ////accumulated monthly consumption
        $schedule->command(RecordMonthlyConsumption::class)->dailyAt('00:09');

        ////update accumulated monthly consumption
        $schedule->command(UpdateMonthlyConsumption::class)->dailyAt('00:12');

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
