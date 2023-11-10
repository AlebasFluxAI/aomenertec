<?php

namespace App\Models\Traits;

use App\Http\Resources\V1\ToastEvent;
use App\Models\V1\Client;
use App\Models\V1\Pqr;
use App\Models\V1\PqrMessage;
use App\Models\V1\Supervisor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

trait PqrTypesTrait
{
    public function getPqrTypes()
    {
        return [
            [
                "key" => __("pqr." . Pqr::PQR_TYPE_TECHNICIAN),
                "value" => Pqr::PQR_TYPE_TECHNICIAN,
            ],
            [
                "key" => __("pqr." . Pqr::PQR_TYPE_PLATFORM),
                "value" => Pqr::PQR_TYPE_PLATFORM,
            ],
            [
                "key" => __("pqr." . Pqr::PQR_TYPE_BILLING),
                "value" => Pqr::PQR_TYPE_BILLING,
            ],

        ];
    }

    public function getSeverity(Component $component)
    {
        $component->severity = Pqr::PQR_SEVERITY_LOW;
        return [
            [
                "key" => __("pqr." . Pqr::PQR_SEVERITY_LOW),
                "value" => Pqr::PQR_SEVERITY_LOW,
            ],
            [
                "key" => __("pqr." . Pqr::PQR_SEVERITY_MEDIUM),
                "value" => Pqr::PQR_SEVERITY_MEDIUM,
            ],
            [
                "key" => __("pqr." . Pqr::PQR_SEVERITY_HIGH),
                "value" => Pqr::PQR_SEVERITY_HIGH,
            ]
        ];
    }

    public function updateType(Component $component)
    {
        $component->pqr_categories = match ($component->pqr_type) {
            Pqr::PQR_TYPE_PLATFORM => $this->getPlatformCategories($component),
            Pqr::PQR_TYPE_TECHNICIAN => $this->getTechnicianCategories($component),
            Pqr::PQR_TYPE_BILLING => $this->getBillingCategories($component),
            default => []
        };
    }

    public function getPlatformCategories(Component $component)
    {
        $component->pqr_category = Pqr::PQR_SUB_TYPE_PLATFORM_ADMIN;
        return [
            [
                "key" => __("pqr." . Pqr::PQR_SUB_TYPE_PLATFORM_ADMIN),
                "value" => Pqr::PQR_SUB_TYPE_PLATFORM_ADMIN,
            ]
        ];
    }

    public function getTechnicianCategories(Component $component)
    {
        $component->pqr_category = Pqr::PQR_SUB_TYPE_ERROR;
        return [
            [
                "key" => __("pqr." . Pqr::PQR_SUB_TYPE_ERROR),
                "value" => Pqr::PQR_SUB_TYPE_ERROR,
            ]
        ];
    }

    public function getBillingCategories(Component $component)
    {
        $component->pqr_category = Pqr::PQR_SUB_TYPE_PAYMENT_AGREE;
        return [
            [
                "key" => __("pqr." . Pqr::PQR_SUB_TYPE_INVOICING),
                "value" => Pqr::PQR_SUB_TYPE_INVOICING,
            ],
            [
                "key" => __("pqr." . Pqr::PQR_SUB_TYPE_OVERRUN),
                "value" => Pqr::PQR_SUB_TYPE_OVERRUN,
            ],
            [
                "key" => __("pqr." . Pqr::PQR_SUB_TYPE_PAYMENT_AGREE),
                "value" => Pqr::PQR_SUB_TYPE_PAYMENT_AGREE,
            ]
        ];
    }

    public function submitForm(Component $component)
    {
        $component->validate([
            'attach' => 'max:10240', // 1MB Max
        ]);
        if (!$this->validateSupervisor($component)) {
            return;
        }
        DB::transaction(function () use ($component) {
            $pqr = Pqr::create($this->mapper($component));
            $pqr->buildOneImageFromFile("attach", $component->attach);
            $component->emitTo(
                'livewire-toast',
                'show',
                ['type' => 'success',
                    'message' => "Se registro la peticion exitosamente"]
            );

            $component->redirectRoute("administrar.v1.peticiones.detalles", ["pqr" => $pqr->id]);
        });
    }

    private function validateSupervisor(Component $component)
    {
        $pqr_mapped = $this->mapper($component);
        if (array_key_exists("supervisor_id", $pqr_mapped)) {
            $supervisor_id = $this->mapper($component)["supervisor_id"];
            $supervisor = Supervisor::find($supervisor_id);
            if (array_key_exists("client_code", $pqr_mapped)) {
                $client = Client::whereCode($pqr_mapped["client_code"])->first();
            } else {
                $client = Client::whereIdentification($pqr_mapped["identification"])->first();
            }

            if (!$client || !in_array($client->id, $supervisor->clients->pluck('id')->toArray())) {
                ToastEvent::launchToast($component, "show", "error", "El cliente no esta vinculado al administrador");
                return false;
            }
        }
        return true;
    }

    public function closePqr(Component $component, $pqr)
    {
    }


    public function submitMessage(Component $component)
    {

        DB::transaction(function () use ($component) {
            $message = $component->model->messages()->create([
                "message" => $component->description,
                "sender_type" => Auth::user() ? PqrMessage::SENDER_TYPE_USER : PqrMessage::SENDER_TYPE_CLIENT,
                "sent_by" => Auth::user() ? Auth::user()->id : $component->model->client_id,
            ]);

            if ($component->attach) {
                $message->buildOneImageFromFile("attach", $component->attach);
            }
        });
        $component->description = "";
        $component->emitTo(
            'livewire-toast',
            'show',
            [
                'type' => 'success',
                'message' => "Se registro la respuesta exitosamente"
            ]
        );
        if ($component->model->status != Pqr::STATUS_PROCESSING) {
            $component->model->update([
                "status" => Pqr::STATUS_PROCESSING
            ]);
        }

        $component->model->refresh();
        $component->attach = null;
        $component->emit("pqr_message_created");
    }

    public function refreshMessages(Component $component)
    {
        $component->model->refresh();
    }
}
