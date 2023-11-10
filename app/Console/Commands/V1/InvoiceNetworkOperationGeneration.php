<?php

namespace App\Console\Commands\V1;

use App\Jobs\GenerateNetworkOperationInvoiceJob;
use App\Models\V1\NetworkOperator;
use Illuminate\Console\Command;

class InvoiceNetworkOperationGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:invoice_generation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generate admin invoice according to invoice day';

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

        foreach (NetworkOperator::get() as $networkOperator) {
            if (now()->day == $networkOperator->billableServices->billing_day) {
                dispatch(new GenerateNetworkOperationInvoiceJob($networkOperator))->onQueue("spot");
            }
        }


    }


}
