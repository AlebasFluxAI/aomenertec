<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\NetworkOperatorPriceTrait;
use Livewire\Component;

class TimelyPaymentService extends Singleton
{
    use EquipmentAssignationTrait;
    use NetworkOperatorPriceTrait;


    public function mount(Component $component, $netwotkOperator)
    {
        $component->model = $netwotkOperator;
        $component->timely_payment_days = $component->model->timelyPayment ? $component->model->timelyPayment->days_to_disconnection : null;
        $component->reconnection_cost = $component->model->timelyPayment ? $component->model->timelyPayment->days_to_payment : null;
        $component->disconnection_days = $component->model->timelyPayment ? $component->model->timelyPayment->reconnection_cost: null;
    }

    public function submitForm(Component $component)
    {
        if ($component->timely_payment_days > $component->disconnection_days) {
            $component->addError("error", "Los dias de desconexion deben ser mayores que los dias para pago oportuno");
            return;
        }
        if ($component->timely_payment_days > 30) {
            $component->addError("error", "Los dias para pago oportuno no pueden ser mas de 30");
            return;
        }
        if ($component->model->timelyPayment) {
            $component->model->timelyPayment->update([
                "days_to_disconnection" => $component->timely_payment_days,
                "days_to_payment" => $component->reconnection_cost,
                "reconnection_cost" => $component->disconnection_days
            ]);
        } else {
            $component->model->timelyPayment()->create([
                "days_to_disconnection" => $component->timely_payment_days,
                "days_to_payment" => $component->reconnection_cost,
                "reconnection_cost" => $component->disconnection_days
            ]);
        }

        ToastEvent::launchToast($component, "show", "success", "Datos modificados exitosamente");

    }


}
