<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\PushRealTimeMicrocontrollerDataJob;
use App\Models\V1\AuxData;
use App\Models\V1\MicrocontrollerData;
use Illuminate\Console\Command;

class EditDataReceived extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:edit_data_received';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $delete = MicrocontrollerData::whereNull('client_id')->where('source_timestamp', '<', '2024-01-28 00:00:00')->get();
        echo (count($delete))."\n";
        foreach ($delete as $item) {
            echo $item->id."\n";
                    dispatch(new PushRealTimeMicrocontrollerDataJob($item->id))->onQueue('spot4');
            }


        //$data = AuxData::where('created_at', '>', '2022-10-09 12:00:00')->get();
        //foreach ($data as $datum) {
         //   MicrocontrollerData::create([
           //     "raw_json" => $datum->data,
            //]);
        //}
        /*$clients = Client::find([91,102,100,99,94,80,]);
        $i = 0;
        while (true){
            foreach ($clients as $client){
                $equipment = $client->equipments()->whereEquipmentTypeId(1)->first();
                $topic = "mc/config/" . $equipment->serial;
                $message['storage_latency'] = 60;
                $message['storage_latency_type'] = 'h';
                $message['did'] = $equipment->serial;
                MQTT::publish($topic, json_encode($message));
                MQTT::disconnect();
            }
            sleep(20);
            $i++;
            if ($i==100){
                break;
            }
        }*/


    }
}
