<?php

namespace App\Models\Traits;

use App\Http\Resources\V1\ToastEvent;
use App\Models\V1\ClientType;
use App\Models\V1\Image;
use App\Models\V1\PhotovoltaicPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

trait NetworkOperatorPriceTrait
{
    public function changeSubsidy(Component $component, $event, $stratum_id)
    {
        if ($price = PhotovoltaicPrice::whereNetworkOperatorId($component->model->id)
            ->where("month", $component->month)
            ->where("year", $component->year)
            ->whereStratumId($stratum_id)->first()) {
            $price->update([
                "subsidy" => $event,
                "stratum_id" => $stratum_id,
                "month" => $component->month,
                "year" => $component->year,
            ]);
        } else {
            PhotovoltaicPrice::create([
                "network_operator_id" => $component->model->id,
                "subsidy" => $event,
                "stratum_id" => $stratum_id,
                "month" => $component->month,
                "year" => $component->year,
            ]);
        }

        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Valor actualizado"]);
    }

    public function changeCredit(Component $component, $event, $stratum_id)
    {
        if ($price = PhotovoltaicPrice::whereNetworkOperatorId($component->model->id)
            ->where("month", $component->month)
            ->where("year", $component->year)
            ->whereStratumId($stratum_id)->first()) {
            $price->update([
                "credit" => $event,
                "stratum_id" => $stratum_id,
                "month" => $component->month,
                "year" => $component->year,
            ]);
        } else {
            PhotovoltaicPrice::create([
                "network_operator_id" => $component->model->id,
                "credit" => $event,
                "stratum_id" => $stratum_id,
                "month" => $component->month,
                "year" => $component->year,
            ]);
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Valor actualizado"]);
    }

    public function changeValue(Component $component, $event, $stratum_id)
    {
        if ($price = PhotovoltaicPrice::whereNetworkOperatorId($component->model->id)
            ->where("month", $component->month)
            ->where("year", $component->year)
            ->whereStratumId($stratum_id)->first()) {
            $price->update([
                "price" => $event,
                "stratum_id" => $stratum_id,
                "month" => $component->month,
                "year" => $component->year,
            ]);
        } else {
            PhotovoltaicPrice::create([
                "network_operator_id" => $component->model->id,
                "price" => $event,
                "stratum_id" => $stratum_id,
                "month" => $component->month,
                "year" => $component->year,
            ]);
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Valor actualizado"]);
    }

    public function getSubsidy(Component $component, $stratum_id)
    {

        if ($price = $component->model->photovoltaicPrice()->whereStratumId($stratum_id)
            ->where("month", $component->month)
            ->where("year", $component->year)
            ->first()) {
            return $price->subsidy;
        }
        return 0;
    }

    public function getCredit(Component $component, $stratum_id)
    {
        if ($price = $component->model->photovoltaicPrice()->whereStratumId($stratum_id)
            ->where("month", $component->month)
            ->where("year", $component->year)
            ->first()) {
            return $price->credit;
        }
        return 0;
    }

    public function getValue(Component $component, $stratum_id)
    {
        if ($price = $component->model->photovoltaicPrice()->whereStratumId($stratum_id)
            ->where("month", $component->month)
            ->where("year", $component->year)
            ->first()) {
            return $price->price;
        }
        return 0;
    }

    public function pickDate(Component $component)
    {
        if (!$component->month or !$component->year) {
            $component->addError('date_picker_error', 'Debes seleccionar el mes y el año');
            return;
        }
        $component->date_picked = true;
        $component->model->refresh();
    }


    public function changeVaupesFeeType(Component $component, $fee, $clientVaupesType, $month, $year, $client_type)
    {

        if ($clientFee = $component->model->vaupesClientStrata()->where([
            "month" => $month,
            "year" => $year,
            "client_type_id" => ClientType::whereType($client_type)->first()->id

        ])->first()) {
            $clientFee->update([
                $clientVaupesType => $fee
            ]);
        } else {
            $component->model->vaupesClientStrata()->create([
                "month" => $month,
                "year" => $year,
                "client_type_id" => ClientType::whereType($client_type)->first()->id,
                $clientVaupesType => $fee
            ]);
        }

        ToastEvent::launchToast($component, "show", "success", "Tarifa cambiada exitosamente");
    }

    public function getVaupesFee(Component $component, $clientVaupesType, $month, $year, $client_type)
    {

        if ($clientFee = $component->model->vaupesClientStrata()->where([
            "month" => $month,
            "year" => $year,
            "client_type_id" => ClientType::whereType($client_type)->first()->id
        ])->first()) {
            return $clientFee->{$clientVaupesType};
        } else {
            return 0.0;
        }
    }
}
