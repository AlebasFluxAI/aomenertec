<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Exports\V1\MonitoringDataExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use PhpMqtt\Client\Facades\MQTT;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataReport extends Component
{
    public $client;
    public $variables;
    public $data_frame;
    public $checks;
    public $start_report;
    public $end_report;
    public $date_range_report;
    public $variables_selected;
    public $time_report_id;
    protected $listeners = ['dateRangeReport', 'selectReport'];

    public function mount(Client $client, $variables, $data_frame)
    {
        $this->time_report_id = 2;
        $this->client = $client;
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $start = Carbon::now();
        $end = Carbon::now();
        $this->start_report = $start->format('Y-m-d 00:00:00');
        $this->end_report = $end->format('Y-m-d 23:59:59');
        $this->date_range_report = $start->format('Y-m-d') . " - " . $end->format('Y-m-d');
        $index = 0;
        $this->variables->push(
            ['id' => 29, 'display_name' => 'Matriz de reactivos']
        );
        $this->checks = collect();
        foreach ($this->variables as $item) {
            $this->checks->push(
                [
                    'id_button' => $item['id'],
                    'label_name' => $item['display_name']
                ]);
            $index++;
        }
    }

    public function updatedTimeReportId()
    {
        if ($this->time_report_id == 2) {
            $this->variables->push(
                ['id' => 29, 'display_name' => 'Matriz de reactivos']
            );
        } else {
            $this->variables = $this->variables->whereNotIn('id', 29);
        }
        dd($this->variables->all());
    }


    public function dateRangeReport($start, $end)
    {
        $aux_start = Carbon::create($start);
        $aux_end = Carbon::create($end);
        $this->date_range_report = $aux_start->format('Y-m-d') . " - " . $aux_end->format('Y-m-d');
        $this->start_report = $start;
        $this->end_report = $end;
    }

    public function reportCsv()
    {
        if ($this->start_report != "") {
            $array = $this->arrayCreate();
            return Excel::download(new MonitoringDataExport($array), 'data_' . $this->client->identification . '_' . Carbon::now()->format('Y-m-d') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    private function arrayCreate()
    {
        if ($this->time_report_id == 1) {
            $data_report = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_report, $this->end_report])->get();
            $array_title = ["ANIO", "MES", "DIA", "HORA", "MINUTO"];
        } elseif ($this->time_report_id == 2) {
            $data_report = $this->client->dailyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_report, $this->end_report])->get();
            $array_title = ["ANIO", "MES", "DIA", "HORA"];
        } elseif ($this->time_report_id == 3) {
            $data_report = $this->client->monthlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_report, $this->end_report])->get();
            $array_title = ["ANIO", "MES", "DIA"];
        } else {
            $data_report = $this->client->annualMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_report, $this->end_report])->get();
            $array_title = ["ANIO", "MES"];
        }
        if (count($data_report) > 0) {
            foreach ($this->variables_selected as $variable) {
                if ($variable != 29)
                    $variables_name = $this->data_frame->where('variable_id', $variable);
                {
                    foreach ($variables_name as $name) {
                        array_push($array_title, $name['display_name']);
                    }
                }
            }
            foreach ($data_report as $index => $data) {
                if ($this->time_report_id == 1) {
                    $array[$index] = [$data->year, $data->month, $data->day, $data->hour, $data->minute];
                    $raw_json = json_decode($data->microcontrollerData->raw_json, true);
                } elseif ($this->time_report_id == 2) {
                    $array[$index] = [$data->year, $data->month, $data->day, $data->hour];
                    $raw_json = json_decode($data->microcontrollerData->raw_json, true);
                } elseif ($this->time_report_id == 3) {
                    $array[$index] = [$data->year, $data->month, $data->day];
                    $raw_json = json_decode($data->raw_json, true);
                } else {
                    $array[$index] = [$data->year, $data->month];
                    $raw_json = json_decode($data->raw_json, true);
                }
                foreach ($this->variables_selected as $variable) {
                    if ($variable != 29) {
                        $variables_name = $this->data_frame->where('variable_id', $variable);
                        foreach ($variables_name as $name) {
                            array_push($array[$index], round($raw_json[$name['variable_name']], 2));
                        }
                    }
                }
            }
            array_unshift($array, $array_title);
            return $array;
        }
    }

    public function reportPdf()
    {
        if ($this->start_report != "") {
            $array = $this->arrayCreate();
            for ($i = 0; $i <= 1; $i++) {
                return Excel::download(new MonitoringDataExport($array), 'data_' . $i . '-' . $this->client->identification . '_' . Carbon::now()->format('Y-m-d') . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
            }
        }
    }

    public function selectReport()
    {
        $equipment = $this->client->equipments()->whereEquipmentTypeId(1)->first();
        RealTimeListener::whereUserId(Auth::user()->id)
            ->whereEquipmentId(
                $equipment->id
            )->delete();

        if (!RealTimeListener::whereEquipmentId(
            $equipment->id)->exists()) {
            $message = "{'did':" . $equipment->serial . ",'realTimeFlag':false}";
            MQTT::publish('mc/config', $message);
            MQTT::disconnect();
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.data-report');
    }
}
