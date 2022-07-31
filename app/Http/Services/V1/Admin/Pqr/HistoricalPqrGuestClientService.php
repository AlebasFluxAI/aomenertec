<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\PqrTypesTrait;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Pqr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HistoricalPqrGuestClientService extends Singleton
{
    use PqrTypesTrait;

    public function submitForm(Component $component)
    {
        if (!$component->client_code) {
            $component->validate();
        }

        $component->validate([
            'attach' => 'image|max:10240', // 1MB Max
        ]);
        DB::transaction(function () use ($component) {
            $pqr = Pqr::create($this->mapper($component));
            $pqr->buildOneImageFromFile("attach", $component->attach);
        });
        $component->emitTo(
            'livewire-toast',
            'show',
            ['type' => 'success',
                'message' => "Se registro la peticion exitosamente"]
        );
        $this->mount($component);
    }

    public function mapper(Component $component)
    {
        return [
            'subject' => $component->subject,
            'client_code' => $component->client_code,
            'description' => $component->description,
            'detail' => $component->description,
            'type' => $component->pqr_type,
            'sub_type' => $component->pqr_category,
            'severity' => $component->severity,
            'contact_name' => $component->contact_name,
            'contact_email' => $component->contact_email,
            'contact_phone' => $component->contact_phone,
            'contact_identification' => $component->contact_identification
        ];
    }

    public function mount(Component $component)
    {
        $component->fill([
            "pqr_type" => Pqr::PQR_TYPE_TECHNICIAN,
            "pqr_types" => $this->getPqrTypes(),
            "pqr_categories" => $this->getTechnicianCategories($component),
            "severities" => $this->getSeverity($component),
            "has_client_code" => false,
        ]);
    }
}
