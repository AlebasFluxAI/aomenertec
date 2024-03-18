<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\JsonEdit;
use App\Jobs\V1\OrderData\AverageDailyConsumptionJob;
use App\Jobs\V1\OrderData\AverageHourlyConsumptionJob;
use App\Jobs\V1\OrderData\AverageMonthlyConsumptionJob;
use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\StopUnpackDataClient;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class RefactorClientData extends Command
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $current_time;
    public $start_date;
    public $date_aux;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:refactor_data_client_last_day';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        $this->current_time = new Carbon();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $clients = Client::whereHasTelemetry(true)->get();
        foreach ($clients as $client) {
            if (!$client->stopUnpackClient()->exists()) {
                StopUnpackDataClient::create(['client_id' => $client->id]);
            }
        }
        $day_search = $this->current_time->copy()->subDays(3);
        $source_date = MicrocontrollerData::where("created_at", '>=', $day_search->format('Y-m-d 00:00:00'))
            ->min('source_timestamp');

        if ($source_date) {
            // $aux = new Carbon('2023-10-01 00:00:00');
            $aux = new Carbon($source_date);
            $date_init = Carbon::create($aux->format('Y'), $aux->format('m'), $aux->format('d'), $aux->format('H'), 0, 0)->format('Y-m-d H:i:s');
            $this->date_aux = new Carbon($date_init);
            $this->unpackData();


            $this->start_date = new Carbon($date_init);
            $start_date_copy = new Carbon($date_init);
            $current_time = $this->current_time->copy();
            $end_date = new Carbon($date_init);
            $end_date_copy = new Carbon($date_init);
            $end_date_first = new Carbon($date_init);
            $i = 0;
            $data = MicrocontrollerData::select('raw_json', 'id', 'source_timestamp')
                ->where('source_timestamp', '>=', $this->start_date->format('Y-m-d H:00:00'))
                ->orderBy('source_timestamp')->limit(100000)->get();
            while (true) {
                echo $this->start_date->format('Y-m-d H-i') . "\n";
                $minute_data = $data->whereBetween('source_timestamp', [$this->start_date->format('Y-m-d H:00:00'), $this->start_date->format('Y-m-d H:59:59')])
                    ->sortBy('source_timestamp')
                    ->all();
                if ($minute_data) {
                    echo "ok\n";
                    foreach ($minute_data as $datum) {
                        dispatch(new JsonEdit($datum, false))->onQueue('spot3');
                    }
                }
                if ($this->start_date->diffInHours($current_time) == 0) {
                    break;
                }
                $this->start_date->addHour();
            }
            $queues = ['spot1', 'spot2', 'spot3', 'spot4', 'spot5', 'reorder_data'];
            while (true) {
                echo $start_date_copy->format('Y-m-d H-i') . "\n";
                $i = 0;
                foreach ($clients as $cliente) {
                    dispatch(new AverageHourlyConsumptionJob($cliente->id, $start_date_copy))->onQueue('spot3');
                    $i++;
                    if ($i == 6) {
                        $i = 0;
                    }
                }
                if ($start_date_copy->diffInHours($current_time) == 0) {
                    break;
                }
                $start_date_copy->addHour();
            }

            while (true) {
                echo "calc day =" . $end_date->format('Y-m-d') . "\n";
                foreach ($clients as $cliente) {
                    dispatch(new AverageDailyConsumptionJob($cliente->id, $end_date))->onQueue('spot3');

                }
                if ($end_date->diffInDays($this->current_time) == 0) {
                    break;
                }
                $end_date->addDay();
            }

            // calculate monthly consumption

            $reference_date = $this->current_time->copy();
            while (true) {
                $reference_date->subDay();
                echo "calc mes =" . $reference_date->format('Y-m-d') . "\n";
                $billing_day = $reference_date->format('d');
                if ($billing_day == $reference_date->format('t')) {
                    $billing_day_clients = ClientConfiguration::whereBillingDay(31)->get()->pluck('client_id');
                } else {
                    $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->orderBy('client_id')->get()->pluck('client_id');
                }
                $clients_aux = Client::whereIn('id', $billing_day_clients)->whereHasTelemetry(true)->select('id')->get()->pluck('id');

                if (count($clients_aux) > 0) {
                    foreach ($clients_aux as $client_aux) {
                        dispatch(new AverageMonthlyConsumptionJob($client_aux, $reference_date))->onQueue('spot3');
                    }
                }
                if ($reference_date->diffInDays($end_date_first) == 0) {
                    break;
                }
            }
        }
    }

    private function unpackData()
    {
        $data_frame = config('data-frame.data_frame');
        $date = Carbon::now();
        MicrocontrollerData::withTrashed()->whereNotNull('deleted_at')
            ->whereBetween("source_timestamp", [$this->date_aux->format('Y-m-d H:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->restore();
        $i = 0;
        $data_pack = MicrocontrollerData::select('id', 'raw_json', 'source_timestamp')
            ->whereNull('client_id')
            ->whereBetween("source_timestamp", [$this->date_aux->format('Y-m-d H:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->orderBy('source_timestamp')
            ->get();
        foreach ($data_pack as &$item) {
            $raw_json = null;
            $raw_json = json_decode($item->raw_json, true);
            if ($raw_json == null) {
                if (strlen($item->raw_json) > 20) {
                    $decode = bin2hex(base64_decode($item->raw_json));
                    $split = substr($decode, (16), (16));
                    $bin = hex2bin($split);
                    $equipment_serial = str_pad(unpack('Q', $bin)[1], 6, "0", STR_PAD_LEFT);
                    $source_timestamp = Carbon::create($item->source_timestamp);
                    if ($date->diffInDays($source_timestamp) <= 365) {

                        foreach ($data_frame as $data) {
                            try {
                                $split = substr($decode, ($data['start']), ($data['lenght']));
                                $bin = hex2bin($split);
                                if (strlen($bin) == ($data['lenght'] / 2)) {
                                    if ($data['start'] >= 450) {
                                        $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                        $json["data_" . $data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                    } else {
                                        if ($data['variable_name'] == "flags") {
                                            $json[$data['variable_name']] = strval(unpack($data['type'], $bin)[1]);
                                        } else {
                                            if ($data['variable_name'] == "equipment_id") {
                                                $json[$data['variable_name']] = $equipment_serial;
                                            } else {
                                                $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                                            }
                                        }
                                    }
                                    if (is_nan($json[$data['variable_name']])) {
                                        $json[$data['variable_name']] = null;
                                    }

                                    if ($data['variable_name'] == "ph3_varLh_acumm") {
                                        break;
                                    }
                                }
                            } catch (Exception $e) {
                                echo 'Excepción capturada: ', $e->getMessage(), "\n";
                            }
                        }

                        $item->raw_json = $json;
                        $item->saveQuietly();
                        echo $i . "\n";
                        $i++;
                    } else {
                        $item->forceDelete();
                    }
                } else {
                    $item->forceDelete();
                }
            } else {
                $item->saveQuietly();
            }
        }
    }

}
