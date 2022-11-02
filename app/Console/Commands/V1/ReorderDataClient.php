<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\MicrocontrollerData;
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
        $id_client = $this->argument('client');
        $client = Client::find($id_client);
        $equipment = $client->equipments()->where('equipment_type_id', 1)->first();
        $search = "\"equipment_id\":\"". $equipment->serial."\"";
        $data_frame = config('data-frame.data_frame');
        foreach (MicrocontrollerData::withTrashed()->where('raw_json', 'like', '%' .$search. '%')->cursor() as $datum) {
            $raw_json = json_decode($datum->raw_json, true);
            foreach ($data_frame as $data) {
                if ($data['start'] >= 450) {
                    $raw_json[$data['variable_name']] = $raw_json["data_" . $data['variable_name']];
                }
                if ($data['variable_name'] == "ph3_varLh_acumm") {
                    break;
                }
            }
            $datum->raw_json = $raw_json;
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
            $datum->restore();
        }
    }
}
