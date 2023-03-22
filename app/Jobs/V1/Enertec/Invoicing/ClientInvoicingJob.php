<?php

namespace App\Jobs\V1\Enertec\Invoicing;

use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientType;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClientInvoicingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $monthlyConsumption = $this->client
            ->monthlyMicrocontrollerData->whereMonth(now()->month)
            ->first()
            ->interval_real_consumption;
        $total_value = $monthlyConsumption;
        $networkOperator = $this->client->networkOperator;

        if ($this->client->clientType->type == ClientType::ZIN_CONVENTIONAL) {

            $zniFee = $networkOperator->model->zniFees()->where([
                "voltage_level_id" => $this->client->voltage_level_id
            ])->first();

            if ($zniFee->optional_fee) {
                $totalToPay = $total_value * $zniFee->optional_fee;
            } else {
                $totalToPay = $total_value * $zniFee->total_fee;
            }

        } else {
            $sinFee = $networkOperator->model->sinFees()->where([
                "voltage_level_id" => $this->client->voltage_level_id
            ])->first();

            if ($sinFee->optional_fee) {
                $totalToPay = $total_value * $sinFee->optional_fee;
            } else {
                $totalToPay = $total_value * $sinFee->total_fee;
            }
        }
    }
}
