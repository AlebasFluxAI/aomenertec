<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\MonthsYears;
use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\RealTimeListener;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;
use Barryvdh\DomPDF\Facade\Pdf;


class ClientInvoiceGenerateService extends Singleton
{
    use WithPagination;

    public function mount(Component $component, Client $client)
    {
        $fechaActual = Carbon::now();
        $component->fill([
            "client" => Client::with('stratum', 'voltageLevel', 'clientType', 'subsistenceConsumption')->find($client->id),
            "network_operator" => $client->networkOperator,
            'months' => MonthsYears::months(),
            'years' => MonthsYears::years(),
            'year' => $fechaActual->year,
            'month' => $fechaActual->month,
            'fees' => $client->feesDate($fechaActual->month, $fechaActual->year),
            'other_fees' => $client->otherFeesDate($fechaActual->month, $fechaActual->year),
        ]);
    }

    public function updatedMonth(Component $component, $value){
        $component->image_uri = null;
        $component->fees = Client::find($component->client->id)->feesDate($value, $component->year);
        $component->other_fees = Client::find($component->client->id)->otherFeesDate($value, $component->year);
        $this->searchDataChart($component);
    }
    public function updatedYear(Component $component,  $value){
        $component->image_uri = null;
        $component->fees = Client::find($component->client->id)->feesDate($component->month, $value);
        $component->other_fees = Client::find($component->client->id)->otherFeesDate($component->month, $value);
        $this->searchDataChart($component);
    }

    private function searchDataChart(Component $component){
        $monthly_data = $component->client->monthlyMicrocontrollerData()
            ->where("month", str_pad($component->month, 2, "0", STR_PAD_LEFT))
            ->where("year", $component->year)->first();

        $value_chart = ['series'=> [], 'x_axis'=> []];
        $date= Carbon::create($component->year, $component->month);
        if($monthly_data) {
            $i = 0;
            while (true) {
                $data = $component->client->monthlyMicrocontrollerData()
                    ->where("month", str_pad($date->format('m'), 2, "0", STR_PAD_LEFT))
                    ->where("year", $date->format('Y'))->first();
                if ($data) {
                    array_push($value_chart['series'], round($data->interval_real_consumption, 2));
                    array_push($value_chart['x_axis'], Carbon::create($data->year, $data->month, $data->day)->format('d M y'));
                } else {
                    array_push($value_chart['series'], 0);
                    array_push($value_chart['x_axis'], $date->format('M y'));
                }
                if ($i == 5) {
                    break;
                }
                $i++;
                $date->subMonth();
            }
            $component->emit('setChartData', $value_chart);
        }else {
            $component->emit('setChartData', $value_chart);
        }
    }
    public function setImageChart(Component $component, $uri)
    {
        $component->image_uri = $uri['imgURI'];
    }

    public function rules()
    {
        return [
            'client.id' => 'required',
            'client.name' => 'required',
            'client.lastname' => 'required',
            'client.code' => 'required',
            'client.identification' => 'required',
            'client.phone' => 'required',
            'client.email' => 'required',
            'client.contribution' => 'required',
            'client.public_lighting_tax' => 'required',
            'client.network_topology' => 'required',

            'client.stratum.id' => 'required',
            'client.stratum.acronym' => 'required',
            'client.stratum.name' => 'required',

            'client.voltageLevel.id' => 'required',
            'client.voltageLevel.level' => 'required',
            'client.voltageLevel.description' => 'required',

            'client.clientType.id' => 'required',
            'client.clientType.type' => 'required',
            'client.clientType.description' => 'required',

            'client.subsistenceConsumption.id' => 'required',
            'client.subsistenceConsumption.value' => 'required',
            'client.subsistenceConsumption.description' => 'required',

            'network_operator.id' => 'required',
            'network_operator.name' => 'required',
            'network_operator.last_name' => 'required',
            'network_operator.identification' => 'required',
            'network_operator.identification_type' => 'required',
            'network_operator.phone' => 'required',
            'network_operator.email' => 'required',
            'network_operator.address' => 'required',
            'network_operator.address_details' => 'required',
            'network_operator.country' => 'required',
            'network_operator.city' => 'required',

            'network_operator.admin.id' => 'required',
            'network_operator.admin.name' => 'required',
            'network_operator.admin.last_name' => 'required',
            'network_operator.admin.identification' => 'required',
            'network_operator.admin.identification_type' => 'required',
            'network_operator.admin.phone' => 'required',
            'network_operator.admin.email' => 'required',
            'network_operator.admin.address' => 'required',
            'network_operator.admin.address_details' => 'required',
            'network_operator.admin.country' => 'required',
            'network_operator.admin.city' => 'required',
            'network_operator.admin.css_file' => 'required',

            'fees.voltage_level_id' => 'required',
            'fees.network_operator_id' => 'required',
            'fees.generation' => 'required',
            'fees.transmission' => 'required',
            'fees.distribution' => 'required',
            'fees.commercialization' => 'required',
            'fees.lost' => 'required',
            'fees.restriction' => 'required',
            'fees.unit_cost' => 'required',
            'fees.optional_fee' => 'required',

            'other_fees.strata_id' => 'required',
            'other_fees.network_operator_id' => 'required',
            'other_fees.tax_type' => 'required',
            'other_fees.contribution' => 'required',
            'other_fees.discount' => 'required',
            'other_fees.tax' => 'required',

        ];
    }

    public function submitForm(Component $component)
    {
        $monthly_data = null;
        $pdf = Pdf::loadView('reports.client_invoice',[
            'monthly_data'=>$monthly_data,
            'client'=>$component->client,
            'network_operator'=> $component->network_operator,
            'admin'=> $component->network_operator->admin,
            'fees'=> $component->fees,
            'other_fees'=> $component->other_fees,
        ]);
        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'export.pdf');
//        $monthly_data = $component->client->monthlyMicrocontrollerData()
//            ->where("month", $component->month)
//            ->where("year", $component->year)->first();
//        if($monthly_data){
//            $json = json_decode($monthly_data->raw_json);
//
//        }

    }
}
