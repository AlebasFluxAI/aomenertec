<?php

namespace App\Console;


use App\Jobs\V1\Enertec\PushRealTimeMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\SaveAlertDataJob;
use App\Jobs\V1\Enertec\SaveMicrocontrollerDataJob;
use App\Jobs\V1\SetConfigJob;
use App\ModulesAux\MQTT;
use Illuminate\Console\Command;

class ConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kafka consumer';

    /**
     * Execute the console command.
     *
     * @return void
     */

    public function handle()
    {
        $mqtt = MQTT::connection('default', 'client_consumer_princi');

        $mqtt->subscribe('v1/mc/alert', function (string $topic, string $message) {
            $pack= base64_encode($message);
            dispatch(new SaveMicrocontrollerDataJob($pack, true))->onQueue('spot');
            dispatch(new SaveAlertDataJob($pack))->onQueue('default');
        }, 0);
        $mqtt->subscribe('mc/data', function (string $topic, string $message) use ($mqtt) {
            //echo $message . "\n";
            dispatch(new SaveMicrocontrollerDataJob($message, false))->onQueue('spot');
        }, 2);
        $mqtt->subscribe('v1/mc/data', function (string $topic, string $message) use ($mqtt) {
            $pack= base64_encode($message);
            dispatch(new SaveMicrocontrollerDataJob($pack, false))->onQueue('spot');
        }, 2);
        //$mqtt->subscribe('mc/alert', function (string $topic, string $message) {
            //echo "msj ALERTA = ".$message."\n";
          //  dispatch(new SaveMicrocontrollerDataJob($message, true))->onQueue('spot');
        //    dispatch(new SaveAlertDataJob($message))->onQueue('default');
      //  }, 0);

        //$mqtt->subscribe('mc/ack', function (string $topic, string $message) {
            //echo $message . "\n";
            //$json = json_decode($message, true);
            //if ($json != null) {
             //   if (array_key_exists('config_get', $json)) {
                    // dispatch(new SetConfigJob($json))->onQueue('spot');
           //     } elseif (array_key_exists('frame_save', $json)) {
                    // dispatch(new SetClientStopUnpackDataJob($json))->onQueue('spot');
         //       }
       //     }
     //   }, 2);
        $mqtt->loop();
    }
}
