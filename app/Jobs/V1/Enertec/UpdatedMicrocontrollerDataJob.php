<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatedMicrocontrollerDataJob implements ShouldQueue
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
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $current_time = new Carbon($this->model->source_timestamp);
        $year = $current_time->format('Y');
        $month = $current_time->format('m');
        $day = $current_time->format('d');
        $hour = $current_time->format('H');
        if ($this->model->interval_real_consumption == 0) {
            $penalizable_inductive = $this->model->interval_reactive_inductive_consumption;
        } else {
            $percent_penalizable_inductive = ($this->model->interval_reactive_inductive_consumption * 100) / $this->model->interval_real_consumption;
            if ($percent_penalizable_inductive >= 50) {
                $penalizable_inductive = ($this->model->interval_real_consumption * $percent_penalizable_inductive / 100) - ($this->model->interval_real_consumption * 0.5);
            } else {
                $penalizable_inductive = 0;
            }
        }
        HourlyMicrocontrollerData::updateOrCreate(
            ['year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $hour,
                'client_id' => $this->model->client_id],
            ['microcontroller_data_id' => $this->model->id,
                'interval_real_consumption' => $this->model->interval_real_consumption,
                'interval_reactive_capacitive_consumption' => $this->model->interval_reactive_capacitive_consumption,
                'interval_reactive_inductive_consumption' => $this->model->interval_reactive_inductive_consumption,
                'penalizable_reactive_capacitive_consumption' => $this->model->interval_reactive_capacitive_consumption,
                'penalizable_reactive_inductive_consumption' => $penalizable_inductive,
                'source_timestamp' => $this->model->source_timestamp,
                'raw_json' => $this->model->raw_json]
        );
    }
}
