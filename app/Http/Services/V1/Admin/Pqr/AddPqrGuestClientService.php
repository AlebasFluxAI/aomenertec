<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\PqrTypesTrait;
use App\Models\V1\Pqr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class AddPqrGuestClientService extends Singleton
{
    use PqrTypesTrait;

    public function submitForm(Component $component)
    {
        if (!$component->client_code) {
            $component->validate();
        }
        if ($component->attach) {
            $component->validate([
                'attach' => 'image|max:10240', // 1MB Max
            ]);
        }
        DB::transaction(function () use ($component) {
            $pqr = Pqr::create($this->mapper($component));
            if ($component->attach) {
                $pqr->buildOneImageFromFile("attach", $component->attach);
            }
            $component->redirectRoute("guest.created-pqr", ["pqr" => $pqr->id, "subdomain" => $component->subdomain]);
        });
    }

    public function mapper(Component $component)
    {
        return array_map(function ($value) {
            return trim($value);
        }, [
            'subject' => $component->subject,
            'client_code' => $component->client_code,
            'description' => $component->description,
            'detail' => $component->description,
            'type' => $component->pqr_type,
            'sub_type' => $component->pqr_category,
            'severity' => $component->severity,
            'level' => Pqr::PQR_LEVEL_1,
            'contact_name' => $component->contact_name,
            'contact_email' => $component->contact_email,
            'contact_phone' => $component->contact_phone,
            'contact_identification' => $component->contact_identification
        ]);
    }

    public function mount(Component $component)
    {
        $component->subdomain = Route::input("subdomain");
        $component->fill([
            "pqr_type" => Pqr::PQR_TYPE_TECHNICIAN,
            "pqr_types" => $this->getPqrTypes(),
            "pqr_categories" => $this->getTechnicianCategories($component),
            "severities" => $this->getSeverity($component),
            "has_client_code" => false,
            "request_equipment" => false
        ]);
    }
}
