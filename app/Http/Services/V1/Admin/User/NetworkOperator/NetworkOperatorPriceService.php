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
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NetworkOperatorPriceService extends Singleton
{
    use EquipmentAssignationTrait;

    public function mount(Component $component)
    {
        $component->fill([
            "model" => User::getUserModel()
        ]);
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
                    $type => $value
                ]);

            } else {
                $component->model->zniOtherFees()->create([
                    "strata_id" => $strata,
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
                    $type => $value
                ]);
            } else {
                $component->model->sinOtherFees()->create([
                    "strata_id" => $strata,
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
                return $fee->{$level};
            }
            return 0.0;
        }
        $fee = $component->model->sinOtherFees()->where([
            "strata_id" => $strata
        ])->first();
        if ($fee) {
            return $fee->{$level};
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
