<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\MicrocontrollerData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

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
        $this->model = $model->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->miningData();
    }

    private function miningData()
    {
        $data_frame = config('data-frame.data_frame');
        $decode = bin2hex(base64_decode($this->model->raw_json));
        foreach ($data_frame as $data) {
            try {
                $split = substr($decode, ($data['start']), ($data['lenght']));
                $bin = hex2bin($split);
                if ($data['start'] >= 440) {
                    $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                    $json["data_".$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                } else {
                    if ($data['variable_name'] == "flags") {
                        $json[$data['variable_name']] = strval(unpack($data['type'], $bin)[1]);
                    } else {
                        $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                    }
                }

                if (is_nan($json[$data['variable_name']])) {
                    $json[$data['variable_name']] = null;
                }

                if ($data['variable_name'] == "ph3_varLh_acumm") {
                    break;
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }
        }
        $this->model->raw_json = $json;
        if ($json['import_wh'] == 0) {
            $this->model->updateQuietly();
            $this->model->delete();
            return;
        }
        $this->model->update();
    }
}
