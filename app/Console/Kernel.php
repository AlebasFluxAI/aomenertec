<?php

namespace App\Console;

use App\Console\Commands\V1\AdminClientEnabledAnnuallyCronjob;
use App\Console\Commands\V1\ClientInvoiceGeneration;
use App\Console\Commands\V1\ClientReport;
use App\Console\Commands\V1\DeleteStopUnpackData;
use App\Console\Commands\V1\OrderData\AverageDaylyConsumptionCommand;
use App\Console\Commands\V1\OrderData\AverageHourlyConsumptionCommand;
use App\Console\Commands\V1\OrderData\AverageMonthlyConsumptionCommand;
use App\Console\Commands\V1\PqrSolvedValidation;
use App\Console\Commands\V1\ReorderDataClientMonth;
use App\Console\Commands\V1\SetTimestamp;
use App\Console\Commands\V1\UpdateDataConsumption;
use App\Console\Commands\V1\UpdateTimestampDataConsumption;
use App\Models\V1\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ConsumerCommand::class,
        ReorderDataClientMonth::class
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
        $schedule->command(UpdateDataConsumption::class)->everyTwoMinutes()->withoutOverlapping();
        $schedule->command(UpdateTimestampDataConsumption::class)->everyMinute()->withoutOverlapping();

        $schedule->command(AverageHourlyConsumptionCommand::class)->hourlyAt(35)->withoutOverlapping();
        $schedule->command(AverageDaylyConsumptionCommand::class)->dailyAt('01:05')->withoutOverlapping();
        $schedule->command(AverageMonthlyConsumptionCommand::class)->dailyAt('2:05')->withoutOverlapping();
        //$schedule->command(RefactorClientData::class)->dailyAt('02:35')->withoutOverlapping(); // si se cambia la frecuencia revisar la hora en que se seleccionan los datos

        $schedule->command(SetTimestamp::class)->twiceDailyAt(10, 22, 3);
        //$schedule->command(SetTimestamp::class)->twiceDailyAt(4, 16, 3);

        $schedule->command(DeleteStopUnpackData::class)->hourlyAt(30);

        $schedule->command(ClientReport::class, [Client::MONTHLY_RATE])
            ->monthlyOn(1, '08:00')
            ->appendOutputTo(storage_path('cron.log'));
        $schedule->command(ClientReport::class, [Client::DAILY_RATE])
            ->dailyAt('08:00')
            ->appendOutputTo(storage_path('cron.log'));

        $schedule->command(ClientInvoiceGeneration::class)
            ->dailyAt('08:00');

        $schedule->command(PqrSolvedValidation::class)
            ->dailyAt('02:00');

        $schedule->command(AdminClientEnabledAnnuallyCronjob::class)
            ->monthlyOn(1);
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
