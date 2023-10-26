<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateReadTypeWorkOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:create_read_type_work_orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $clientesSinRegistros = Client::whereDoesntHave('microcontrollerData', function ($query) {
            $query->where('source_timestamp', '>=', Carbon::now()->subHours(48));
        })->get()->pluck('id');
        dd($clientesSinRegistros);
    }
}
