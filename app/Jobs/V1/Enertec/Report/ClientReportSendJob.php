<?php

namespace App\Jobs\V1\Enertec\Report;

use App\Exports\V1\MultipleSheetsMonitoringData;
use App\Http\Resources\V1\Icon;
use App\Models\V1\Client;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ClientReportSendJob implements ShouldQueue
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
    public $rate;


    public function
    __construct($rate)
    {
        $this->rate = $rate;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Client::where("report_rate", $this->rate)->get() as $client) {
            try {
                $filePath = 'reporte_' . $client->alias . '_' . Carbon::now()->format('Y-m-d H:i:s') . '.xlsx';
                $array = $this->arrayCreate($client, $client->report_variables, 1);
                if (!$array) {
                    continue;
                }
                Excel::store(new MultipleSheetsMonitoringData($array),
                    $filePath,
                    "public");

                Mail::send("mail.v1.report_client", [
                    "user" => $client,
                    "logo_url" => Icon::getUserIconUser($client),
                ], function ($message) use ($client, $filePath) {
                    $message->to($client->email)
                        ->attach((Storage::disk("public")->path($filePath)))
                        ->subject("Reporte automatico");
                });

                Storage::disk("public")->delete($filePath);
            } catch (\Throwable) {
                continue;
            }
        }

    }

    private function arrayCreate($client, $variables, $report_time_id)
    {
        $variables = explode(",", str_replace(array('[', ']', '"'), '', $variables));
        [$start_report, $end_report] = $this->timeRange($client);
        $data_frame = collect(config('data-frame.data_frame'));
        if ($report_time_id == 1) {
            $data_report = $client->microcontrollerData()
                ->whereBetween("source_timestamp", [$start_report, $end_report])
                ->whereIsAlert(false)
                ->orderBy('source_timestamp')
                ->limit(15000)->get();
            $array_title = ["ANIO", "MES", "DIA", "HORA", "MINUTO"];
        } elseif ($report_time_id == 2) {
            $data_report = $client->hourlyMicrocontrollerData()
                ->whereBetween("source_timestamp", [$start_report, $end_report])
                ->orderBy('source_timestamp')
                ->limit(15000)->get();
            $array_title = ["ANIO", "MES", "DIA", "HORA"];
        } elseif ($report_time_id == 3) {
            $data_report = $client->dailyMicrocontrollerData()
                ->whereHas('microcontrollerData', function ($query) use ($start_report, $end_report) {
                    $query->whereBetween("source_timestamp", [$start_report, $end_report]);
                })
                ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                ->limit(15000)->get();
            $array_title = ["ANIO", "MES", "DIA"];
        } else {
            $data_report = $client->monthlyMicrocontrollerData()
                ->whereHas('microcontrollerData', function ($query) use ($start_report, $end_report) {
                    $query->whereBetween("source_timestamp", [$start_report, $end_report]);
                })
                ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                ->limit(15000)->get();
            $array_title = ["ANIO", "MES"];
        }
        if (count($data_report) > 0) {
            foreach ($variables as $variable) {
                if ($variable != 33) {
                    $variables_name = $data_frame->where('variable_id', $variable);
                    foreach ($variables_name as $name) {
                        array_push($array_title, $name['display_name']);
                    }
                }
            }
            foreach ($data_report as $index => $data) {
                if ($report_time_id == 1) {
                    $date = Carbon::create($data->source_timestamp);
                    $array[$index] = [intval($date->format('Y')), intval($date->format('m')), intval($date->format('d')), intval($date->format('H')), intval($date->format('i'))];
                    $raw_json = json_decode($data->raw_json, true);
                } elseif ($report_time_id == 2) {
                    $array[$index] = [intval($data->year), intval($data->month), intval($data->day), intval($data->hour)];
                    $raw_json = json_decode($data->raw_json, true);
                } elseif ($report_time_id == 3) {
                    $array[$index] = [intval($data->year), intval($data->month), intval($data->day)];
                    $raw_json = json_decode($data->raw_json, true);
                } else {
                    $array[$index] = [intval($data->year), intval($data->month)];
                    $raw_json = json_decode($data->raw_json, true);
                }
                foreach ($variables as $variable) {
                    if ($variable != 33) {
                        $variables_name = $data_frame->where('variable_id', $variable);
                        foreach ($variables_name as $name) {
                            array_push($array[$index], round($raw_json[$name['variable_name']], 2));
                        }
                    }
                }
            }
            array_unshift($array, $array_title);
            $array_report = [$array];
            if (in_array(33, $variables)) {
                $array_penalizable = $this->arrayCreateReactive();
                foreach ($array_penalizable as $item) {
                    array_push($array_report, $item);
                }
            }
            return $array_report;
        }
    }

    private function timeRange($client): array
    {
        if ($client->report_rate == Client::DAILY_RATE) {
            return [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()];
        } else {
            return [now()->subMonth(), now()];
        }
    }
}
