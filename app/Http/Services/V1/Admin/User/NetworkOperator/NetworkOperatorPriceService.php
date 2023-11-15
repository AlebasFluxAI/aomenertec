<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\MonthsYears;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Jobs\V1\Enertec\ClientInvoiceGenerationMonthYearJob;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\ClientType;
use App\Models\V1\Stratum;
use App\Models\V1\User;
use App\Models\V1\ZniLevelFee;
use Livewire\Component;

class NetworkOperatorPriceService extends Singleton
{
    use EquipmentAssignationTrait;
    use NetworkOperatorPriceTrait;


    public function mount(Component $component, $client_type)
    {
        $component->fill([
            "model" => User::getUserModel(),
            'months' => MonthsYears::months(),
            'years' => MonthsYears::years(),
            "date_picked" => false,
            "client_type" => $client_type,
            "has_invoice_generation" => false
        ]);
        $this->fillStrataArray($component);

    }

    public function fillStrataArray(Component $component)
    {
        $component->taxType[ClientType::ZIN_CONVENTIONAL] = [];
        $component->taxType[ClientType::SIN_CONVENTIONAL] = [];

        foreach (Stratum::get() as $strata) {
            $component->taxType[ClientType::ZIN_CONVENTIONAL] [strval($strata->id)] = ZniLevelFee::MONEY_FEE;
            $component->taxType[ClientType::SIN_CONVENTIONAL][strval($strata->id)] = ZniLevelFee::MONEY_FEE;
        }

        foreach ($component->model->zniOtherFees()->get() as $zniFees) {
            $component->taxType[ClientType::ZIN_CONVENTIONAL][strval($zniFees->strata_id)] = $zniFees->tax_type;
        }

        foreach ($component->model->sinOtherFees()->get() as $sinFees) {
            $component->taxType[ClientType::SIN_CONVENTIONAL][strval($sinFees->strata_id)] = $sinFees->tax_type;
        }
    }

    public function generateOtherClientInvoicing(Component $component)
    {
        $clients = $component->model->clients()->whereClientTypeId(ClientType::whereType($component->client_type)->first()->id)->get();
        foreach ($clients as $clients) {
            dispatch(new ClientInvoiceGenerationMonthYearJob($clients, $component->year, $component->month));
        }
        ToastEvent::launchToast($component, "show", "success", "Facturas generadas correctamente");
    }

    public function getPercentageOption(Component $component, $strata, $clientType)
    {
        return $component->taxType[$clientType][$strata];
    }

    public function changeOptionalFee(Component $component, $value, $level, $type, $client_type)
    {
        $this->changeFeeFunction($component, $value, $level, $type, $client_type);
    }

    public function changeFeeFunction(Component $component, $value, $level, $type, $client_type)
    {
        if ($client_type == ClientType::ZIN_CONVENTIONAL) {
            if ($fee = $component->model->zniFees()->where([
                "voltage_level_id" => $level,
                "month" => $component->month,
                "year" => $component->year,
            ])->first()) {
                if ($type == "generation") {
                    $fee->update([
                        $type => $value,
                        "lost" => ($value / 0.9) - $value
                    ]);
                } else {
                    $fee->update([
                        $type => $value
                    ]);
                }


            } else {
                if ($type == "generation") {
                    $component->model->zniFees()->create([
                        "voltage_level_id" => $level,
                        "month" => $component->month,
                        "year" => $component->year,
                        "lost" => ($value / 0.9) - $value,
                        $type => $value
                    ]);
                } else {
                    $component->model->zniFees()->create([
                        "voltage_level_id" => $level,
                        "month" => $component->month,
                        "year" => $component->year,
                        $type => $value
                    ]);
                }


            }
        } else {
            if ($fee = $component->model->sinFees()->where([
                "voltage_level_id" => $level,
                "month" => $component->month,
                "year" => $component->year,
            ])->first()) {
                $fee->update([
                    $type => $value
                ]);
            } else {
                $component->model->sinFees()->create([
                    "voltage_level_id" => $level,
                    "month" => $component->month,
                    "year" => $component->year,
                    $type => $value
                ]);
            }
        }
        ToastEvent::launchToast($component, "show", "success", "Valor actualizado");

    }

    public function getOptionalFee(Component $component, $value, $level, $type)
    {
        return $this->getFeeFunction($component, $value, $level, $type);
    }

    private function getFeeFunction(Component $component, $value, $level, $type)
    {
        if ($type == ClientType::ZIN_CONVENTIONAL) {
            $fee = $component->model->zniFees()->where([
                "voltage_level_id" => $value,
                "month" => $component->month,
                "year" => $component->year,
            ])->first();
            if ($fee) {
                return $fee->{$level};
            }
            return 0.0;
        }
        $fee = $component->model->sinFees()->where([
            "voltage_level_id" => $value,
            "month" => $component->month,
            "year" => $component->year,
        ])->first();
        if ($fee) {
            return $fee->{$level};
        }
        return 0.0;
    }

    public function changeFee(Component $component, $value, $level, $type, $client_type)
    {
        $this->changeFeeFunction($component, $value, $level, $type, $client_type);
    }

    public function getFee(Component $component, $value, $level, $type)
    {
        return $this->getFeeFunction($component, $value, $level, $type);
    }

    public function changeTaxTypeStrata(Component $component, $value, $strata, $client_type)
    {
        $component->taxType[$client_type][strval($strata)] = $value;
        if ($client_type == ClientType::ZIN_CONVENTIONAL) {
            if ($component->model->zniOtherFees()->where([
                "strata_id" => $strata,
                "month" => $component->month,
                "year" => $component->year,
            ])->exists()) {
                $fee = $component->model->zniOtherFees()->where([
                    "strata_id" => $strata,
                    "month" => $component->month,
                    "year" => $component->year,
                ])->first();
                $fee->update([
                    "tax_type" => $component->taxType[$client_type][$strata],
                ]);
            } else {
                $component->model->zniOtherFees()->create([
                    "strata_id" => $strata,
                    "tax_type" => $component->taxType[$client_type][$strata],
                    "month" => $component->month,
                    "year" => $component->year,
                ]);
            }
        } else {
            if ($component->model->sinOtherFees()->where([
                "strata_id" => $strata,
                "month" => $component->month,
                "year" => $component->year,
            ])->exists()) {
                $fee = $component->model->sinOtherFees()->where([
                    "strata_id" => $strata,
                    "month" => $component->month,
                    "year" => $component->year,
                ]);
                $fee->update([
                    "tax_type" => $component->taxType[$client_type][$strata],
                ]);
            } else {
                $component->model->sinOtherFees()->create([
                    "strata_id" => $strata,
                    "tax_type" => $component->taxType[$client_type][$strata],
                    "month" => $component->month,
                    "year" => $component->year,
                ]);
            }
        }
        ToastEvent::launchToast($component, "show", "success", "Valor actualizado");

    }

    public function changeOtherFee(Component $component, $type, $value, $strata, $client_type)
    {
        if ($client_type == ClientType::ZIN_CONVENTIONAL) {
            if ($component->model->zniOtherFees()->where([
                "strata_id" => $strata,
                "month" => $component->month,
                "year" => $component->year,
            ])->exists()) {
                $fee = $component->model->zniOtherFees()->where([
                    "strata_id" => $strata,
                    "month" => $component->month,
                    "year" => $component->year,
                ])->first();
                $fee->update([
                    $type => $value,
                    "tax_type" => $component->taxType[$client_type][$strata],

                ]);
            } else {
                $component->model->zniOtherFees()->create([
                    "strata_id" => $strata,
                    "tax_type" => $component->taxType[$client_type][$strata],
                    $type => $value,
                    "month" => $component->month,
                    "year" => $component->year,
                ]);
            }
        } else {
            if ($component->model->sinOtherFees()->where([
                "strata_id" => $strata,
                "month" => $component->month,
                "year" => $component->year,
            ])->exists()) {
                $fee = $component->model->sinOtherFees()->where([
                    "strata_id" => $strata,
                    "month" => $component->month,
                    "year" => $component->year,
                ]);
                $fee->update([
                    $type => $value,
                    "tax_type" => $component->taxType[$client_type][$strata],


                ]);
            } else {
                $component->model->sinOtherFees()->create([
                    "strata_id" => $strata,
                    "tax_type" => $component->taxType[$client_type][$strata],
                    $type => $value,
                    "month" => $component->month,
                    "year" => $component->year,
                ]);
            }
        }
        ToastEvent::launchToast($component, "show", "success", "Valor actualizado");


    }

    public function getOtherFee(Component $component, $value, $strata, $type)
    {

        if ($type == ClientType::ZIN_CONVENTIONAL) {
            $fee = $component->model->zniOtherFees()->where([
                "strata_id" => $strata,
                "month" => $component->month,
                "year" => $component->year,
            ])->first();

            if ($fee) {
                return $fee->{$value};
            }
            return 0.0;
        }
        $fee = $component->model->sinOtherFees()->where([
            "strata_id" => $strata,
            "month" => $component->month,
            "year" => $component->year,
        ])->first();
        if ($fee) {
            return $fee->{$value};
        }
        return 0.0;
    }


}
