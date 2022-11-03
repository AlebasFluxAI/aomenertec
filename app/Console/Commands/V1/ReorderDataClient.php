<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
use App\Models\V1\Client;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\StopUnpackDataClient;
use Illuminate\Console\Command;

class ReorderDataClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:reorder_data_client
                            {client : ID client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reorder data client, parameter id client';

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
        $start_date = '2022-09-14 07:15:00';
        $id_client = $this->argument('client');
        $client = Client::find($id_client);
        if (!$client->stopUnpackClient()->exists()) {
            StopUnpackDataClient::create(['client_id' => $client->id]);
        }
        $equipment = $client->equipments()->where('equipment_type_id', 1)->first();
        $search = "\"equipment_id\":\"". $equipment->serial."\"";
        $search_1 = "\"equipment_id\":". $equipment->serial;
        MicrocontrollerData::withTrashed()
            ->where('source_timestamp', '>', $start_date)
            ->where('client_id', $id_client)
            ->where('raw_json', 'like', '%' .$search. '%')
            ->orWhere('raw_json', 'like', '%' .$search_1. '%')

        ->chunk(200, function ($data) {
            foreach ($data as $datum) {
                $datum->client_id = null;
                $datum->accumulated_real_consumption = null;
                $datum->interval_real_consumption = null;
                $datum->accumulated_reactive_consumption = null;
                $datum->interval_reactive_consumption = null;
                $datum->accumulated_reactive_capacitive_consumption = null;
                $datum->interval_reactive_capacitive_consumption = null;
                $datum->accumulated_reactive_inductive_consumption = null;
                $datum->interval_reactive_inductive_consumption = null;
                $datum->saveQuietly();
                if ($datum->trashed()){
                    $datum->restore();
                }
            }
        });
        $data_pack = MicrocontrollerData::whereNull('client_id')
                                    ->whereNotNull('source_timestamp')
                                    ->where('raw_json', 'like', '%' .$search. '%')
                                    ->orWhere('raw_json', 'like', '%' .$search_1. '%')
                                    ->orderBy('source_timestamp')->orderBy('created_at')
                                    ->get();
        if ($data_pack) {
            foreach ($data_pack as $item) {
                $raw_json = json_decode($item->raw_json, true);
                $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'] ;
                $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'] ;
                $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'] ;
                $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'] ;
                $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'] ;
                $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'] ;
                $item->raw_json = $raw_json;
                $item->save();
                dispatch(new SerializeMicrocontrollerDataJob($item))->onQueue('reorder_data');
            }
        }
    }
}
