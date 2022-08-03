<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\AuxData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

class BackupMicrocontrollerDataJob implements ShouldQueue
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
    public $model;

    public function __construct(MicrocontrollerData $model)
    {
        $this->model = $model->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateData();
    }

    private function updateData()
    {
        $decode = bin2hex(base64_decode($this->model->raw_json));
        $timestamp = (unpack('l', hex2bin(substr($decode, 64, 8)))[1]);
        $date = new Carbon();
        $date->setTimestamp($timestamp);
        $this->model->source_timestamp = $date->format("Y-m-d H:i:s");
        AuxData::create([
            'data'=> $this->model->raw_json
        ]);
        $this->model->saveQuietly();
    }
}
