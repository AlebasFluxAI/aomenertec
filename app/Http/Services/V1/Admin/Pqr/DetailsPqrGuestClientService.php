<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\PqrTypesTrait;
use Livewire\Component;

class DetailsPqrGuestClientService extends Singleton
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
            'contact_name' => $component->contact_name,
            'contact_email' => $component->contact_email,
            'contact_phone' => $component->contact_phone,
            'contact_identification' => $component->contact_identification
        ];
    }

    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $messages = $component->messages;
    }
}
