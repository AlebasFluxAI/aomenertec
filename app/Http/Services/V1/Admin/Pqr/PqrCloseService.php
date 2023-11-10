<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\PqrStatusTrait;
use App\Models\V1\PqrMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PqrCloseService extends Singleton
{
    use PqrStatusTrait;

    public function mount(Component $component, $model)
    {
        $component->model = $model;
    }

    public function submitCloserMessage(Component $component)
    {
        DB::transaction(function () use ($component) {
            $message = $component->model->closeMessage()->create([
                "message" => $component->description,
                "sender_type" => Auth::user() ? PqrMessage::SENDER_TYPE_USER : PqrMessage::SENDER_TYPE_CLIENT,
                "sent_by" => Auth::user() ? Auth::user()->id : $component->model->client_id,
                "type" => PqrMessage::MESSAGE_TYPE_CLOSER
            ]);

            if ($component->attach) {
                $message->buildOneImageFromFile("attach", $component->attach);
            }
        });

        $component->description = "";
        $component->emitTo(
            'livewire-toast',
            'show',
            ['type' => 'success',
                'message' => "Se registro la respuesta exitosamente"]
        );
        $component->model->refresh();
        $component->attach = null;
        $component->emit("pqr_message_created");
        if ($this->solvePqr($component, $component->model->id)) {
            $component->redirectRoute("administrar.v1.peticiones.detalles", ["pqr" => $component->model->id]);
        }


    }
}
