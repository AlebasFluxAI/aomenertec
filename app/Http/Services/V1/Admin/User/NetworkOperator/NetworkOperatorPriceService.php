<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Livewire\V1\Admin\User\AssignedEquipmentInterface;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\ClientType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Stratum;
use App\Models\V1\User;
use App\Models\V1\ZniLevelFee;
use App\Models\V1\ZniOtherFee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use function Livewire\str;

class NetworkOperatorPriceService extends Singleton
{
    use EquipmentAssignationTrait;

    public function mount(Component $component)
    {
        $component->fill([
            "model" => User::getUserModel()
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

    public function getPercentageOption(Component $component, $strata, $clientType)
    {
        return $component->taxType[$clientType][$strata];
    }

    public function changeOptionalFee(Component $component, $value, $level, $type, $client_type)
    {
        $this->changeFeeFunction($component, $value, $level, $type, $client_type);
    }


    public function getOptionalFee(Component $component, $value, $level, $type)
    {
        return $this->getFeeFunction($component, $value, $level, $type);
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
            ])->exists()) {
                $fee = $component->model->zniOtherFees()->where([
                    "strata_id" => $strata,
                ])->first();
                $fee->update([
                    "tax_type" => $component->taxType[$client_type][$strata],
                ]);
            } else {
                $component->model->zniOtherFees()->create([
                    "strata_id" => $strata,
                    "tax_type" => $component->taxType[$client_type][$strata],
                ]);
            }
        } else {
            if ($component->model->sinOtherFees()->where([
                "strata_id" => $strata,
            ])->exists()) {
                $fee = $component->model->sinOtherFees()->where([
                    "strata_id" => $strata,
                ]);
                $fee->update([
                    "tax_type" => $component->taxType[$client_type][$strata],
                ]);
            } else {
                $component->model->sinOtherFees()->create([
                    "strata_id" => $strata,
                    "tax_type" => $component->taxType[$client_type][$strata],
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
            ])->exists()) {
                $fee = $component->model->zniOtherFees()->where([
                    "strata_id" => $strata,
                ])->first();
                $fee->update([
                    $type => $value,
                    "tax_type" => $component->taxType[$client_type][$strata],

                ]);
            } else {
                $component->model->zniOtherFees()->create([
                    "strata_id" => $strata,
                    "tax_type" => $component->taxType[$client_type][$strata],
                    $type => $value
                ]);
            }
        } else {
            if ($component->model->sinOtherFees()->where([
                "strata_id" => $strata,
            ])->exists()) {
                $fee = $component->model->sinOtherFees()->where([
                    "strata_id" => $strata,
                ]);
                $fee->update([
                    $type => $value,
                    "tax_type" => $component->taxType[$client_type][$strata],

                ]);
            } else {
                $component->model->sinOtherFees()->create([
                    "strata_id" => $strata,
                    "tax_type" => $component->taxType[$client_type][$strata],
                    $type => $value
                ]);
            }
        }
        ToastEvent::launchToast($component, "show", "success", "Valor actualizado");
    }

    public function getOtherFee(Component $component, $value, $strata, $type)
    {

        if ($type == ClientType::ZIN_CONVENTIONAL) {
            $fee = $component->model->zniOtherFees()->where([
                "strata_id" => $strata
            ])->first();

            if ($fee) {
                return $fee->{$value};
            }
            return 0.0;
        }
        $fee = $component->model->sinOtherFees()->where([
            "strata_id" => $strata
        ])->first();
        if ($fee) {
            return $fee->{$value};
        }
        return 0.0;
    }

    private function getFeeFunction(Component $component, $value, $level, $type)
    {
        if ($type == ClientType::ZIN_CONVENTIONAL) {
            $fee = $component->model->zniFees()->where([
                "voltage_level_id" => $value
            ])->first();
            if ($fee) {
                return $fee->{$level};
            }
            return 0.0;
        }
        $fee = $component->model->sinFees()->where([
            "voltage_level_id" => $value
        ])->first();
        if ($fee) {
            return $fee->{$level};
        }
        return 0.0;
    }

    public function changeFeeFunction(Component $component, $value, $level, $type, $client_type)
    {

        if ($client_type == ClientType::ZIN_CONVENTIONAL) {
            if ($component->model->zniFees()->where([
                "voltage_level_id" => $level,
            ])->exists()) {
                $fee = $component->model->zniFees()->where([
                    "voltage_level_id" => $level,
                ])->first();
                $fee->update([
                    $type => $value
                ]);

            } else {
                $component->model->zniFees()->create([
                    "voltage_level_id" => $level,
                    $type => $value
                ]);
            }
        } else {
            if ($component->model->sinFees()->where([
                "voltage_level_id" => $level,
            ])->exists()) {
                $fee = $component->model->sinFees()->where([
                    "voltage_level_id" => $level,
                ]);
                $fee->update([
                    $type => $value
                ]);
            } else {
                $component->model->sinFees()->create([
                    "voltage_level_id" => $level,
                    $type => $value
                ]);
            }
        }
        ToastEvent::launchToast($component, "show", "success", "Valor actualizado");

    }

}
