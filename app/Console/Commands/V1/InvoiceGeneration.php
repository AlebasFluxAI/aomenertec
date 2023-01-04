<?php

namespace App\Console\Commands\V1;

use App\Jobs\GenerateAdminInvoiceJob;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\HourlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InvoiceGeneration extends Command
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

        foreach (Admin::get() as $admin) {
            if ($admin->invoicing_day != Carbon::parse(now())->format('d')) {
                continue;
            }
            dispatch(new GenerateAdminInvoiceJob($admin))->onQueue("spot");
        }


    }


}
