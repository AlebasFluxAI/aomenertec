<?php

namespace App\Console\Commands\V1;

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
        $delete = MicrocontrollerData::where('source_timestamp','>', '2022-09-30 19:00:00')->get();
        foreach ($delete as $item){
            if ($item->hourlyMicrocontrollerData){
                $item->hourlyMicrocontrollerData->delete();
            }
            if ($item->dailyMicrocontrollerData){
                $item->dailyMicrocontrollerData->delete();
            }
            if ($item->clientAlert){
                $item->clientAlert->delete();
            }
            $item->delete();
        }
        $data = AuxData::where('created_at','>', '2022-09-30 19:00:00')->get();
        foreach ($data as $datum){
            MicrocontrollerData::create([
                "raw_json" => $datum->data,
            ]);
        }
    }
}
