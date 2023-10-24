<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Resources\V1\TimeZoneHelper;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Http\Resources\V1\Icon;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
use App\Models\Traits\ImageableTrait;
use App\Models\V1\Admin;
use App\Models\V1\BillingInformation;
use App\Models\V1\ClientSupervisor;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Invoice;
use App\Models\V1\InvoicePaymentRegistration;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class ClientManualPaymentRegisterService extends Singleton
{


    public function mount(Component $component, Invoice $invoice)
    {
        $component->fill([
            "model" => $invoice,
            "client" => $invoice->client,
            "payment_methods" => InvoicePaymentRegistration::paymentMethodKeyValue()
        ]);
    }


    public function registerPayment(Component $component)
    {

        $component->validate([
            'evidence' => 'image|max:10240', // 1MB Max
        ]);

        $paymentRecord = $component->model->paymentRecord()->create(
            [

                "payment_method" => $component->payment_method,
                "reference" => $component->reference,
                "other_payment_method" => $component->other_payment_method,
                "bank" => $component->bank,
            ]
        );
        $paymentRecord->buildOneImageFromFile("evidence", $component->evidence);
        $component->redirectRoute("v1.admin.client.manual_payment", ["client" => $component->client->id]);

    }


}
