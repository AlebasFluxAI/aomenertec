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
        $search_1 = "\"equipment_id\":". $equipment->serial;
        MicrocontrollerData::withTrashed()->where('raw_json', 'like', '%' .$search. '%')->orWhere('raw_json', 'like', '%' .$search_1. '%')
        //MicrocontrollerData::where('client_id', $id_client)
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
    }
}
