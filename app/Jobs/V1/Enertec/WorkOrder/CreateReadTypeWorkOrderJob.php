<?php

namespace App\Jobs\V1\Enertec\WorkOrder;

use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateReadTypeWorkOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $client;

    public function __construct($client_id)
    {
        $this->client = \App\Models\V1\Client::find($client_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userModel = $this->client->networkOperator->user;
        $technician = $this->client->clientTechnician()->first();
        if ($technician == null) {
            $technician = $this->client->networkOperator->technicians()->first();
        }
        if ($technician) {
            $technicianModel = $technician->user;
            $a = $this->client->workOrders()->create([
                "status" => WorkOrder::WORK_ORDER_STATUS_OPEN,
                "open_at" => Carbon::now(),
                "open_by" => $technicianModel->id,
                "description" => "lectura",
                "type" => WorkOrder::WORK_ORDER_TYPE_READING,
                "technician_id" => $technician->id,
                "created_by_type" => $userModel::class,
                "created_by_id" => $userModel->id
            ]);
            echo $a->id . "\n";
        }
    }
}
