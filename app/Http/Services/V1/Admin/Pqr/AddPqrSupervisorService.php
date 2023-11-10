<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Resources\V1\Menu;
use App\Http\Services\Singleton;
use App\Models\Traits\PqrTypesTrait;
use App\Models\V1\Pqr;
use Livewire\Component;

class AddPqrSupervisorService extends Singleton
{
    use PqrTypesTrait;

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
            'contact_name' => $component->model->name,
            'contact_email' => $component->model->email,
            'contact_phone' => $component->model->phone,
            'contact_identification' => $component->model->identification,
            "supervisor_id" => $component->model->id,
            "level" => Pqr::PQR_LEVEL_2,
        ];
    }


    public function mount(Component $component)
    {
        $model = Menu::getUserModel();
        $component->model = $model;
        $component->fill([
            "pqr_type" => Pqr::PQR_TYPE_TECHNICIAN,
            "pqr_types" => $this->getPqrTypes(),
            "pqr_categories" => $this->getTechnicianCategories($component),
            "severities" => $this->getSeverity($component),
            "has_client_code" => false,
        ]);
    }
}
