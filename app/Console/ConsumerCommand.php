<?php

namespace App\Console;


use App\Jobs\V1\Enertec\PushRealTimeMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\SaveAlertDataJob;
use App\Jobs\V1\Enertec\SaveMicrocontrollerDataJob;
use App\Jobs\V1\Api\ConfigurationClient\SetConfigJob;
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
        $mqtt->subscribe('v1/mc/data', function (string $topic, string $message) use ($mqtt) {

            $pack= base64_encode($message);
            // echo $pack."\n";
            dispatch(new SaveMicrocontrollerDataJob($pack, false))->onQueue('spot');
        }, 2);
        $mqtt->subscribe('v1/mc/alert', function (string $topic, string $message) {
            $pack= base64_encode($message);
            sleep(3);
            echo "alerta = ".$pack."\n";
            //dispatch(new SaveMicrocontrollerDataJob($pack, true))->onQueue('spot');
            dispatch(new SaveAlertDataJob($pack, false))->onConnection('sync');
        }, 0);
        $mqtt->subscribe('v1/mc/alert_control', function (string $topic, string $message) {
            $pack= base64_encode($message);
            //sleep(5);
            echo "alerta control = ".$pack."\n";
            //dispatch(new SaveMicrocontrollerDataJob($pack, true))->onQueue('spot');
            dispatch(new SaveAlertDataJob($pack, true))->onQueue('default');
        }, 0);
        $mqtt->subscribe('v1/mc/ack', function (string $topic, string $message) {
            if (substr($message, 0, 2) == '21') {
                $hex = $message;
            } else{
                $hex = bin2hex($message);
            }
            dispatch(new SetConfigJob($hex))->onQueue('spot1');
        }, 0);
        $mqtt->subscribe('mc/data', function (string $topic, string $message) use ($mqtt) {
            // echo "message= ".$message."\n";

            dispatch(new SaveMicrocontrollerDataJob($message, false))->onQueue('spot');
        }, 2);
        $mqtt->subscribe('v1/mc/real_time', function (string $topic, string $message) use ($mqtt) {
            $pack= base64_encode($message);
            dispatch(new PushRealTimeMicrocontrollerDataJob($pack))->onQueue('default');
        }, 0);
        $mqtt->loop();
    }
}
