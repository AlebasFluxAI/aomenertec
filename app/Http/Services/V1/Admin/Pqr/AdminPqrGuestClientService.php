<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\PqrStatusTrait;
use App\Models\V1\Client;
use App\Models\V1\Pqr;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class AdminPqrGuestClientService extends Singleton
{
    use PqrStatusTrait;

    public function submitForm(Component $component)
    {
        $component->pqrs = [];
        $client = Client::whereCode($component->client_code)->first();

        if (!$client) {
            $component->addError('client_code', 'El codigo de cliente no existe');
            return;
        }

        $pqr = Pqr::whereCode($component->pqr_code)->first();
        if (!$pqr) {
            $component->addError('pqr_code', 'El codigo de pqr no existe');
            return;
        }
        if ($pqr->client != $client) {
            $component->addError('pqr_code', 'El codigo de pqr no existe');
            return;
        }

        $component->pqrs = Pqr::whereCode($component->pqr_code)->get();
        $component->resetErrorBag();
    }


    public function mount(Component $component)
    {
        $component->subdomain = Route::input("subdomain");
    }
}
