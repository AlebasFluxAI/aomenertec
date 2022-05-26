<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;
use App\Exports\V1\MultipleSheetsMonitoringData;
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
    protected $listeners = ['dateRangeReport', 'selectReport'];
    public $client;
    public $variables;
    public $data_frame;
    public $checks;
    public $start_report;
    public $end_report;
    public $date_range_report;
    public $variables_selected;
    public $time_report_id;


    public function mount(Client $client, $variables, $data_frame){
        $this->time_report_id = 2;
        $this->client = $client;
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $start = Carbon::now();
        $end = Carbon::now();
        $this->start_report = $start->format('Y-m-d 00:00:00');
        $this->end_report = $end->format('Y-m-d 23:59:59');
        $this->date_range_report = $start->format('Y-m-d')." - ".$end->format('Y-m-d');
        $index = 0;
        $this->variables->push(
                    ['id'=>29,'display_name'=>'Matriz de reactivos']
            );

    }

    public function dateRangeReport($start, $end)
    {
        $aux_start = Carbon::create($start);
        $aux_end = Carbon::create($end);
        $this->date_range_report = $aux_start->format('Y-m-d')." - ".$aux_end->format('Y-m-d');
        $this->start_report = $start;
        $this->end_report = $end;
    }

    private function arrayCreateReactive(){

        $title = ["DIA/HORA", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"];
        $end_day = Carbon::create($this->end_report);
        $start_day = Carbon::create($this->start_report);
        $aux_day = Carbon::create($end_day);
        $active = [];
        $inductive = [];
        $capacitive = [];
        $inductive_pen = [];
        $capacitive_pen = [];
        $days = $aux_day->diffInDays($start_day);
        for ($i=0; $i<=$days; $i++){
            if ($i == 0){
                $data_report = $this->client->dailyMicrocontrollerData()
                    ->whereDate('created_at', $end_day->format('Y-m-d'))->get();
            } else{
                $data_report = $this->client->dailyMicrocontrollerData()
                    ->whereDate('created_at', ($end_day->subDay(1)->format('Y-m-d')))->get();
            }
            if (count($data_report)>0) {
                $aux_active = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $aux_inductive = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $aux_capacitive = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $aux_inductive_pen = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $aux_capacitive_pen = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                foreach ($data_report as $index => $data){
                    $aux_active[intval($data->hour)] = $data->interval_real_consumption;
                    $aux_inductive[intval($data->hour)] = $data->interval_reactive_inductive_consumption;
                    $aux_capacitive[intval($data->hour)] = $data->interval_reactive_capacitive_consumption;
                    $aux_inductive_pen[intval($data->hour)] = $data->penalizable_reactive_inductive_consumption;
                    $aux_capacitive_pen[intval($data->hour)] = $data->penalizable_reactive_capacitive_consumption;
                    if ($index == 0) {
                        $day = Carbon::create($data->microcontrollerData->source_timestamp)->format('Y-m-d');
                    }
                }
                array_unshift($aux_active, $day);
                array_unshift($aux_inductive, $day);
                array_unshift($aux_capacitive, $day);
                array_unshift($aux_inductive_pen, $day);
                array_unshift($aux_capacitive_pen, $day);
                array_push($active, $aux_active);
                array_push($inductive, $aux_inductive);
                array_push($capacitive, $aux_capacitive);
                array_push($inductive_pen, $aux_inductive_pen);
                array_push($capacitive_pen, $aux_capacitive_pen);
            }
        }
        array_unshift($active, $title);
        array_unshift($inductive, $title);
        array_unshift($capacitive, $title);
        array_unshift($inductive_pen, $title);
        array_unshift($capacitive_pen, $title);
        $array_penalizable = [$active, $inductive, $capacitive, $inductive_pen, $capacitive_pen];
        return $array_penalizable;
    }

    private function arrayCreate(){
        if ($this->time_report_id == 1){
            $data_report = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_report, $this->end_report])
                ->limit(1440)->get();
            $array_title = ["ANIO", "MES", "DIA", "HORA", "MINUTO"];
        } elseif ($this->time_report_id == 2){
            $data_report = $this->client->dailyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_report, $this->end_report])
                ->limit(1440)->get();
            $array_title = ["ANIO", "MES", "DIA", "HORA"];
        } elseif($this->time_report_id == 3){
            $data_report = $this->client->monthlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_report, $this->end_report])
                ->limit(720)->get();
            $array_title = ["ANIO", "MES", "DIA"];
        } else{
            $data_report = $this->client->annualMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_report, $this->end_report])
                ->limit(24)->get();
            $array_title = ["ANIO", "MES"];
        }
        $array = [];
        if (count($data_report)>0) {
            foreach ($this->variables_selected as $variable) {
                if ($variable != 29)
                {
                    $variables_name = $this->data_frame->where('variable_id', $variable);
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
        }
        $array_report = [$array];
        if (in_array(29, $this->variables_selected)){
            $array_penalizable = $this->arrayCreateReactive();
            foreach ($array_penalizable as $item){
                array_push($array_report, $item);
            }
        }
        return $array_report;
    }

    public function reportCsv(){
        if ($this->start_report != ""){
            $array = $this->arrayCreate();
            return Excel::download(new MultipleSheetsMonitoringData($array), 'data_' . $this->client->identification . '_' . Carbon::now()->format('Y-m-d H:i:s') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    public function reportPdf(){
        if ($this->start_report != ""){
            $array = $this->arrayCreate();
            /*for ($i=0; $i<=1; $i++){
                return Excel::download(new MonitoringDataExport($array), 'data_'.$i.'-' . $this->client->identification . '_' . Carbon::now()->format('Y-m-d') . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
            }*/
        }
    }

    public function selectReport(){
        $equipment =$this->client->equipmentsClient()->whereEquipmentTypeId(1)->first();
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
