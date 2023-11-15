<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\MonthsYears;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Jobs\V1\Enertec\ClientInvoiceGenerationMonthYearJob;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\ClientType;
use App\Models\V1\Stratum;
use Livewire\Component;

class NetworkOperatorPriceConfigurationService extends Singleton
{
    use NetworkOperatorPriceTrait;

    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
            'months' => MonthsYears::months(),
            'years' => MonthsYears::years(),
            "date_picked" => false,
            "has_invoice_generation" => false,
            "client_type" => ClientType::ZIN_PHOTOVOLTAIC
        ]);
    }

    public function generatePhotovoltaicInvoicing(Component $component)
    {
        $clients = $component->model->clients()->whereClientTypeId(ClientType::whereType(ClientType::ZIN_PHOTOVOLTAIC)->first()->id)->get();
        foreach ($clients as $clients) {
            dispatch(new ClientInvoiceGenerationMonthYearJob($clients, $component->year, $component->month));
        }
        ToastEvent::launchToast($component, "show", "success", "Facturas generadas correctamente");
    }

    public function getData(Component $component)
    {
        return Stratum::get();
    }
}
