<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Livewire\V1\Admin\User\AssignedEquipmentInterface;
use App\Http\Resources\V1\MonthsYears;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\NetworkOperatorPriceTrait;
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

class TimelyPaymentService extends Singleton
{
    use EquipmentAssignationTrait;
    use NetworkOperatorPriceTrait;


    public function mount(Component $component, $netwotkOperator)
    {
        
    }


}
