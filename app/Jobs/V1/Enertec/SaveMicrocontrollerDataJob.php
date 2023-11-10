<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\AuxData;
use App\Models\V1\MicrocontrollerData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveMicrocontrollerDataJob implements ShouldQueue
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
    public $raw_json;
    public $flag;

    public function __construct($raw_json, $is_alert)
    {
        $this->raw_json = $raw_json;
        $this->flag = $is_alert;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = MicrocontrollerData::create([
            "raw_json" => $this->raw_json,
            "is_alert" => $this->flag,
        ]);

        AuxData::create([
            'data' => $this->raw_json
        ]);
    }
}
