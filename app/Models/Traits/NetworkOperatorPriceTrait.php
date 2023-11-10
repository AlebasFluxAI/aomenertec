<?php

namespace App\Models\Traits;

use App\Http\Resources\V1\ToastEvent;
use App\Models\V1\ClientType;
use App\Models\V1\PhotovoltaicPrice;
use App\Models\V1\Stratum;
use App\Models\V1\VoltageLevel;
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
        if ($component->client_type == ClientType::ZIN_CONVENTIONAL) {
            if ($fee = $component->model->zniFees()->where([
                "month" => $component->month,
                "year" => $component->year
            ])->first()) {
                $component->default_rate = $fee->default_rate;
            }
        } elseif ($component->client_type == ClientType::SIN_CONVENTIONAL) {
            if ($fee = $component->model->sinFees()->where([
                "month" => $component->month,
                "year" => $component->year
            ])->first()) {
                $component->default_rate = $fee->default_rate;
            }
        } else {
            if ($fee = $component->model->photovoltaicPrice()->where([
                "month" => $component->month,
                "year" => $component->year
            ])->first()) {
                $component->default_rate = $fee->default_rate;
            }
        }
    }

    public function updatedDefaultRate(Component $component, $value)
    {

        if ($component->client_type == ClientType::ZIN_CONVENTIONAL) {
            if ($component->model->zniFees()->where([
                "month" => $component->month,
                "year" => $component->year
            ])->exists()) {
                foreach ($component->model->zniFees()->where([
                    "month" => $component->month,
                    "year" => $component->year
                ])->get() as $fee) {
                    $fee->default_rate = $value;
                    $fee->save();
                }
            } else {
                foreach (VoltageLevel::get() as $level) {
                    $component->model->zniFees()->create([
                        "voltage_level_id" => $level->id,
                        "default_rate" => $value,
                        "month" => $component->month,
                        "year" => $component->year,
                    ]);
                }
            }
        } elseif ($component->client_type == ClientType::SIN_CONVENTIONAL) {
            if ($component->model->sinFees()->where([
                "month" => $component->month,
                "year" => $component->year
            ])->exists()) {
                foreach ($component->model->sinFees()->where([
                    "month" => $component->month,
                    "year" => $component->year
                ])->get() as $fee) {
                    $fee->default_rate = $value;
                    $fee->save();
                }
            } else {
                foreach (VoltageLevel::get() as $level) {
                    $component->model->sinFees()->create([
                        "voltage_level_id" => $level->id,
                        "default_rate" => $value,
                        "month" => $component->month,
                        "year" => $component->year,
                    ]);
                }
            }
        } else {
            if ($component->model->photovoltaicPrice()->where([
                "month" => $component->month,
                "year" => $component->year
            ])->exists()) {
                foreach ($component->model->photovoltaicPrice()->where([
                    "month" => $component->month,
                    "year" => $component->year
                ])->get() as $fee) {
                    $fee->default_rate = $value;
                    $fee->save();
                }
            } else {
                foreach (Stratum::get() as $strata) {

                    $component->model->photovoltaicPrice()->create([
                        "stratum_id" => $strata->id,
                        "default_rate" => $value,
                        "month" => $component->month,
                        "year" => $component->year,
                    ]);
                }
            }
        }
        ToastEvent::launchToast($component, "show", "success", "Valor actualizado");

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
