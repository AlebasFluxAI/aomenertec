<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientInvoiceGenerateService;
use App\Models\V1\Client;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SinOtherFee;
use App\Models\V1\SubsistenceConsumption;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class ClientInvoiceGenerate extends Component
{
    private $clientInvoiceGenerateService;
    public $client;
    public $fees;
    public $network_operator;
    public $other_fees;
    public $month;
    public $year;
    public $months;
    public $years;
    public $image_uri;
    protected $listeners = ['setImageChart'];

    public function __construct()
    {
        parent::__construct();
        $this->clientInvoiceGenerateService = ClientInvoiceGenerateService::getInstance();
    }
    public function setImageChart($uri)
    {
        $this->clientInvoiceGenerateService->setImageChart($this, $uri);
    }
    public function submitForm()
    {
        $monthly_data = $this->client->monthlyMicrocontrollerData()
            ->where("month", str_pad($this->month, 2, "0", STR_PAD_LEFT))
            ->where("year", $this->year)->first();
        if($monthly_data){
            $sc = SubsistenceConsumption::find($this->client->subsistence_consumption_id);
            $value_kwh = (($this->fees->optional_fee == 0)?$this->fees->unit_cost:$this->fees->optional_fee);
            $value_active = $monthly_data->interval_real_consumption * $value_kwh;
            $value_tax = ($this->client->public_lighting_tax)?(($this->other_fees->tax_type == SinOtherFee::MONEY_FEE)?$this->other_fees->tax:$value_active*$this->other_fees->tax/100):0;
            $value_discount_kwh = $value_kwh * $this->other_fees->discount/100;
            $value_discount = ($monthly_data->interval_real_consumption > $sc->value)?($sc->value * $value_discount_kwh * (-1)):($monthly_data->interval_real_consumption* $value_discount_kwh * (-1));
            $value = collect([]);
            $value->value_active= $value_active;
            $value->value_contribution = ($this->client->stratum->id > 4)?(($this->client->contribution && $this->other_fees->contribution > 0)?$value_active*$this->other_fees->contribution/100:0):0;
            $value->value_discount = ($this->client->stratum->id < 4)?(($this->other_fees->discount > 0)?$value_discount:0):0;
            $value->value_tax = $value_tax;
            $value->value_varch = $this->fees->distribution * $monthly_data->interval_reactive_capacitive_consumption;
            $value->value_varlh = $this->fees->distribution * $monthly_data->penalizable_reactive_inductivo_consumption;
            $value->subtotal_energy = $value->value_active + $value->value_contribution + $value->value_discount + $value->value_tax + $value->value_varch + $value->value_varlh;
            $value->subtotal_others = 0;
            $value->total = $value->subtotal_energy + $value->subtotal_others;
            $json = json_decode($monthly_data->raw_json);
            $pdf = PDF::loadView('reports.client_invoice',[
                'image_chart_url' => $this->image_uri,
                'value' => $value,
                'json' => $json,
                'monthly_data'=>$monthly_data,
                'client'=>Client::find($this->client->id),
                'network_operator'=> $this->network_operator,
                'admin'=> NetworkOperator::find($this->network_operator->id)->admin,
                'fees'=> $this->fees,
                'other_fees'=> $this->other_fees,
            ]);
            $pdf->setPaper('A4', 'portrait');
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'export.pdf');
        }

    }

    public function mount(Client $client)
    {
        $this->clientInvoiceGenerateService->mount($this, $client);
    }
     public function updatedMonth($value){
         $this->clientInvoiceGenerateService->updatedMonth($this, $value);

     }
     public function updatedYear($value){
         $this->clientInvoiceGenerateService->updatedYear($this, $value);

     }

    protected function rules()
    {
        return $this->clientInvoiceGenerateService->rules($this);
    }
    public function render()
    {
        return view('livewire.v1.admin.client.client-invoice-generate')->extends('layouts.v1.app');
    }
}
