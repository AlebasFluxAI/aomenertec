<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\ClientAlert;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTimestampDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $item;

    public function __construct(MicrocontrollerData $item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->item->status = MicrocontrollerData::PENDING_TIMESTAMP;
        $this->item->saveQuietly();
        $this->item = MicrocontrollerData::find($this->item->id);
        if ($this->item->source_timestamp == null) {
            if (json_decode($this->item->raw_json, true) == null) {
                if (strlen($this->item->raw_json) > 20) {
                    $decode = bin2hex(base64_decode($this->item->raw_json));
                    $timestamp = (unpack('l', hex2bin(substr($decode, 64, 8)))[1]);
                    $date = new Carbon();
                    $date->setTimestamp($timestamp);
                    $current_time = Carbon::now();
                    if ($date->diffInDays($current_time) > 360) {
                        if ($alert = ClientAlert::where('microcontroller_data_id', $this->item->id)) {
                            $alert->forceDelete();
                        }
                        if ($order = WorkOrder::where('microcontroller_data_id', $this->item->id)) {
                            $order->forceDelete();
                        }
                        if ($datum = MicrocontrollerData::find($this->item->id)) {
                            $datum->status = MicrocontrollerData::PENDING_TIMESTAMP;
                            $datum->forceDelete();
                        }
                    } else {
                        $this->item->source_timestamp = $date->format("Y-m-d H:i:s");
                        $this->item->status = MicrocontrollerData::SUCCESS_TIMESTAMP;
                        $this->item->saveQuietly();
                    }
                } else {
                    if ($alert = ClientAlert::where('microcontroller_data_id', $this->item->id)) {
                        $alert->forceDelete();
                    }
                    if ($order = WorkOrder::where('microcontroller_data_id', $this->item->id)) {
                        $order->forceDelete();
                    }
                    if ($datum = MicrocontrollerData::find($this->item->id)) {
                        $datum->forceDelete();
                    }
                }
            } else {
                $raw_json = json_decode($this->item->raw_json, true);
                if (array_key_exists('timestamp',$raw_json )) {
                    $timestamp = $raw_json['timestamp'];
                    $date = new Carbon();
                    $date->setTimestamp($timestamp);
                    $current_time = Carbon::now();
                    if ($date->diffInDays($current_time) > 360) {
                        if ($alert = ClientAlert::where('microcontroller_data_id', $this->item->id)) {
                            $alert->forceDelete();
                        }
                        if ($order = WorkOrder::where('microcontroller_data_id', $this->item->id)) {
                            $order->forceDelete();
                        }
                        if ($datum = MicrocontrollerData::find($this->item->id)) {
                            $datum->status = MicrocontrollerData::PENDING_TIMESTAMP;
                            $datum->forceDelete();
                        }
                    } else {
                        $this->item->source_timestamp = $date->format("Y-m-d H:i:s");
                        $this->item->status = MicrocontrollerData::SUCCESS_TIMESTAMP;
                        $this->item->saveQuietly();
                    }
                } else {
                    if ($alert = ClientAlert::where('microcontroller_data_id', $this->item->id)) {
                        $alert->forceDelete();
                    }
                    if ($order = WorkOrder::where('microcontroller_data_id', $this->item->id)) {
                        $order->forceDelete();
                    }
                    if ($datum = MicrocontrollerData::find($this->item->id)) {
                        $datum->status = MicrocontrollerData::PENDING_TIMESTAMP;
                        $datum->forceDelete();
                    }
                }
            }
        }
    }
}
