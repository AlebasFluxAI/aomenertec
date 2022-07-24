<?php

namespace App\Models\Traits;

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
            ->whereStratumId($stratum_id)->first()) {
            $price->update([
                "subsidy" => $event,
                "stratum_id" => $stratum_id
            ]);

        } else {
            PhotovoltaicPrice::create([
                "network_operator_id" => $component->model->id,
                "subsidy" => $event,
                "stratum_id" => $stratum_id
            ]);
        }

        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Valor actualizado"]);

    }

    public function changeCredit(Component $component, $event, $stratum_id)
    {
        if ($price = PhotovoltaicPrice::whereNetworkOperatorId($component->model->id)
            ->whereStratumId($stratum_id)->first()) {
            $price->update([
                "credit" => $event,
                "stratum_id" => $stratum_id
            ]);

        } else {
            PhotovoltaicPrice::create([
                "network_operator_id" => $component->model->id,
                "credit" => $event,
                "stratum_id" => $stratum_id
            ]);
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Valor actualizado"]);


    }

    public function changeValue(Component $component, $event, $stratum_id)
    {
        if ($price = PhotovoltaicPrice::whereNetworkOperatorId($component->model->id)
            ->whereStratumId($stratum_id)->first()) {
            $price->update([
                "value" => $event,
                "stratum_id" => $stratum_id
            ]);


        } else {
            PhotovoltaicPrice::create([
                "network_operator_id" => $component->model->id,
                "value" => $event,
                "stratum_id" => $stratum_id
            ]);
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Valor actualizado"]);


    }

    public function getSubsidy(Component $component, $stratum_id)
    {

        if ($price = $component->model->photovoltaicPrice()->whereStratumId($stratum_id)->first()) {
            return $price->subsidy;
        }
        return 0;
    }

    public function getCredit(Component $component, $stratum_id)
    {
        if ($price = $component->model->photovoltaicPrice()->whereStratumId($stratum_id)->first()) {
            return $price->credit;
        }
        return 0;
    }

    public function getValue(Component $component, $stratum_id)
    {
        if ($price = $component->model->photovoltaicPrice()->whereStratumId($stratum_id)->first()) {
            return $price->price;
        }
        return 0;
    }
}
