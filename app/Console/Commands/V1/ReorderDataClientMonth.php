<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataMonthJob;
use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\MonthlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReorderDataClientMonth extends Command
{
    /**
     * Create a new command instance.
     *
     * @return void
     */

    public $current_time;
    public $date_init;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:reorder_monthly_data_client {date_init} {clients_id?*}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->current_time = new Carbon();
        $this->date_init = new Carbon($this->argument('date_init'));
        $clients_id = $this->argument('clients_id');
        while (true) {
            $this->date_init->addDay();
            echo "calc mes =" . $this->date_init->format('Y-m-d') . "\n";
            $billing_day = $this->date_init->format('d');
            if ($billing_day == $this->date_init->format('t')) {
                $billing_day_clients = ClientConfiguration::whereBillingDay(31)->get()->pluck('client_id');
            } else {
                $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->orderBy('client_id')->get()->pluck('client_id');
            }
            $clients_aux_total = Client::whereIn('id', $billing_day_clients)->whereHasTelemetry(true)->select('id')->get()->pluck('id');
            if (count($clients_id) > 0) {
                $clients_aux = [];

                foreach ($clients_aux_total as $elemento1) {
                    foreach ($clients_id as $elemento2) {
                        if ($elemento1 == $elemento2) {
                            $clients_aux[] = $elemento1;
                            break;
                        }
                    }
                }
            } else {
                $clients_aux = $clients_aux_total;
            }
            if (count($clients_aux) > 0) {
                foreach ($clients_aux as $client_aux) {
                    echo $client_aux . "\n";
                    $this->data($this->date_init->format('Y-m-d H:00:00'), $client_aux);
                    dispatch(new SerializeMicrocontrollerDataMonthJob($this->date_init->format('Y-m-d H:00:00'), $client_aux))->onQueue('spot3');
                }
            }
            if ($this->date_init->diffInDays($this->current_time) == 0) {
                break;
            }
        }
    }

    public function data($date, $client_id)
    {
        $day_ref = new Carbon($date);
        $client = Client::find($client_id);
        $billing_day = $day_ref->format('d');
        if ($day_ref->format('m') == '01') {
            $month_aux = 12;
            $year_aux = $day_ref->format('Y') - 1;
        } else {
            $month_aux = $day_ref->format('m') - 1;
            if ($month_aux < 10) {
                $month_aux = '0' . $month_aux;
            }
            $year_aux = $day_ref->format('Y');
        }
        if ($billing_day == $day_ref->format('t')) {
            $date_aux = Carbon::create($year_aux, $month_aux, 2);
            $start_date = Carbon::create($year_aux, $month_aux, $date_aux->format('t'), 23, 59, 59);
            $end_date = Carbon::create($day_ref->format('Y'), $day_ref->format('m'), $day_ref->format('t'), 23, 59, 59);
        } else {
            $start_date = Carbon::create($year_aux, $month_aux, ($billing_day), 23, 59, 59);
            $end_date = Carbon::create($day_ref->format('Y'), $day_ref->format('m'), $billing_day, "23", "59", 59);
        }
        if ($billing_day == $day_ref->format('t')) {
            $data_month = $client->dailyMicrocontrollerData()
                ->where('year', $day_ref->format('Y'))
                ->where('month', $day_ref->format('m'))
                ->whereBetween('day', ['01', $billing_day])
                ->get();
        } else {
            $data_aux = $client->dailyMicrocontrollerData()
                ->where('year', $year_aux)
                ->where('month', ($month_aux))
                ->whereBetween('day', [str_pad((strval(($billing_day + 1))), 2, "0", STR_PAD_LEFT), ($start_date->format('t'))]);
            $data_month = $client->dailyMicrocontrollerData()
                ->where('year', $day_ref->format('Y'))
                ->where('month', $day_ref->format('m'))
                ->whereBetween('day', ['01', $billing_day])
                ->union($data_aux)
                ->get();
        }
        if (count($data_month) > 0) {
            $end_data = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();
            if ($end_data) {

                $date_end_data = new Carbon($end_data->source_timestamp);
                $start_data_aux = $client->monthlyMicrocontrollerData()
                    ->where('year', $start_date->format('Y'))
                    ->where('month', $start_date->format('m'))->first();
                if (empty($start_data_aux)) {
                    $start_data = $client->microcontrollerData()
                        ->whereDate('source_timestamp', '<', $start_date->format('Y-m-d 23:59:59'))
                        ->orderBy('source_timestamp', 'desc')
                        ->first();
                    if (empty($start_data)) {
                        $start_data = $client->microcontrollerData()
                            ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                            ->orderBy('source_timestamp')
                            ->first();
                    } else {
                        $date_start_data = new Carbon($start_data->source_timestamp);
                        if ($date_start_data->diffInDays($day_ref) > 40) {
                            $start_data = $client->microcontrollerData()
                                ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                                ->orderBy('source_timestamp')
                                ->first();
                        }
                    }

                } else {
                    $start_data = $start_data_aux->microcontrollerData;
                }
                if ($end_data) {
                    $interval_active_month = $end_data->accumulated_real_consumption - $start_data->accumulated_real_consumption;
                    if ($interval_active_month < 0) {
                        $start_data = $client->microcontrollerData()
                            ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                            ->orderBy('source_timestamp')
                            ->first();
                    }
                    $reference_data = $end_data;
                    $json = json_decode($reference_data->raw_json, true);
                    $penalizable_inductive_month = 0;
                    $penalizable_capacitive_month = 0;
                    $interval_active_month = $end_data->accumulated_real_consumption - $start_data->accumulated_real_consumption;
                    $interval_capacitive_month = $end_data->accumulated_reactive_capacitive_consumption - $start_data->accumulated_reactive_capacitive_consumption;
                    $interval_inductive_month = $end_data->accumulated_reactive_inductive_consumption - $start_data->accumulated_reactive_inductive_consumption;
                    foreach ($data_month as $item) {
                        $penalizable_inductive_month = $penalizable_inductive_month + $item->penalizable_reactive_inductive_consumption;
                        $penalizable_capacitive_month = $penalizable_capacitive_month + $item->penalizable_reactive_capacitive_consumption;
                    }
                    $json_first = json_decode($start_data->raw_json, true);
                    $json['kwh_interval'] = $json['import_wh'] - $json_first['import_wh'];
                    $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $json_first['ph1_import_kwh'];
                    $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $json_first['ph2_import_kwh'];
                    $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $json_first['ph3_import_kwh'];
                    $json['varh_interval'] = $json['import_VArh'] - $json_first['import_VArh'];
                    $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $json_first['ph1_import_kvarh'];
                    $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $json_first['ph2_import_kvarh'];
                    $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $json_first['ph3_import_kvarh'];
                    $json['varCh_interval'] = $json['varCh_acumm'] - $json_first['varCh_acumm'];
                    $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - $json_first['ph1_varCh_acumm'];
                    $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - $json_first['ph2_varCh_acumm'];
                    $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - $json_first['ph3_varCh_acumm'];
                    $json['varLh_interval'] = $json['varLh_acumm'] - $json_first['varLh_acumm'];
                    $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - $json_first['ph1_varLh_acumm'];
                    $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - $json_first['ph2_varLh_acumm'];
                    $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - $json_first['ph3_varLh_acumm'];

                    MonthlyMicrocontrollerData::updateOrCreate([
                        'year' => $day_ref->format('Y'),
                        'month' => $day_ref->format('m'),
                        'day' => $billing_day,
                        'client_id' => $client->id],
                        ['microcontroller_data_id' => $reference_data->id,
                            'interval_real_consumption' => $interval_active_month,
                            'interval_reactive_capacitive_consumption' => $interval_capacitive_month,
                            'interval_reactive_inductive_consumption' => $interval_inductive_month,
                            'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_month,
                            'penalizable_reactive_inductive_consumption' => $penalizable_inductive_month,
                            'raw_json' => json_encode($json),
                        ]);
                }
            }
        }
    }

}
