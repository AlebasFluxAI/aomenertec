<?php

namespace App\Console;

use App\Console\Commands\V1\ClientInvoicingCommand;
use App\Console\Commands\V1\ClientReport;
use App\Console\Commands\V1\DeleteStopUnpackData;
use App\Console\Commands\V1\InvoiceGeneration;
use App\Console\Commands\V1\RefactorClientData;
use App\Console\Commands\V1\ReorderDataClientDay;
use App\Console\Commands\V1\ReorderDataClientHour;
use App\Console\Commands\V1\SetTimestamp;
use App\Console\Commands\V1\RecordDailyConsumption;
use App\Console\Commands\V1\RecordMonthlyConsumption;
use App\Console\Commands\V1\UpdateDailyConsumption;
use App\Console\Commands\V1\UpdateDataConsumption;
use App\Console\Commands\V1\UpdateMonthlyConsumption;
use App\Console\Commands\V1\UpdateTimestampDataConsumption;
use App\Jobs\V1\Enertec\SaveMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\AuxData;
use App\Models\V1\Client;
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
        $schedule->command(UpdateDataConsumption::class)->everyThreeMinutes()->withoutOverlapping();
        $schedule->command(UpdateTimestampDataConsumption::class)->everyMinute()->withoutOverlapping();
        $schedule->command(SetTimestamp::class)->twiceDailyAt(10, 22, 3);
        $schedule->command(SetTimestamp::class)->twiceDailyAt(4, 16, 3);

        // $schedule->command(RefactorClientData::class)->dailyAt('01:32')->withoutOverlapping();
        $schedule->command(DeleteStopUnpackData::class)->everyThirtyMinutes();

        $schedule->command(InvoiceGeneration::class)->dailyAt(2);
        $schedule->command(ClientReport::class, [Client::MONTHLY_RATE])
            ->monthlyOn(1, '08:00')
            ->appendOutputTo(storage_path('cron.log'));
        $schedule->command(ClientReport::class, [Client::DAILY_RATE])
            ->dailyAt('08:00')
            ->appendOutputTo(storage_path('cron.log'));
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
