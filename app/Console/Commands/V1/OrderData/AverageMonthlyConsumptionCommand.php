<?php

namespace App\Console\Commands\V1\OrderData;

use Illuminate\Console\Command;

class AverageMonthlyConsumptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:order_data:average_monthly_consumption_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'El comando se ejecuta cada dia a las 1:30. Se encarga de actualizar el consumo mensual y de promediar los meses de no registro de datos, dependiendo la fecha de corte de cada cliente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
