<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Services\Singleton;
use App\Models\Traits\AddUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminEditService extends Singleton
{
    use AddUserFormTrait;

    public function mount(Component $component, Admin $model)
    {
        $component->fill([
            "styles" => Admin::styles(),
            "decodedAddress" => $model->address,
            "identification_types" => $this->identificationTypes($model->person_type),
            'person_types' => [
                ["key" => "Persona natural", "value" => User::PERSON_TYPE_NATURAL],
                ["key" => "Persona juridica", "value" => User::PERSON_TYPE_JURIDICAL]
            ],
            "model" => $model,
            "latitude" => $model->latitude,
            "longitude" => $model->longitude,
            "indicatives" => IndicativeHelper::getIndicativesKeyValue()
        ]);
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            if ($component->icon) {
                $image = $component->icon;
                if (!$component->model->icon) {
                    $component->model->buildOneImageFromFile("icon", $image);
                } else {
                    $component->model->icon->setDataImage($image);
                    $component->model->icon->name = $image->getClientOriginalName();
                    $component->model->icon->update();
                }
            }
            $component->model->latitude = $component->latitude;
            $component->model->longitude = $component->longitude;
            $component->validate([
                'model.identification' => 'required|min:6',
                'model.name' => 'required|min:6',
                'model.last_name' => 'required|min:6',
                'model.phone' => 'min:7',
                'model.email' => 'required|email',
                'model.address_details' => 'required',
                'model.latitude' => 'required',
                'model.longitude' => 'required',
                'model.billing_name' => 'required',
                'model.billing_address' => 'required',
                'model.person_type' => 'required',
                'model.identification_type' => 'required',
                'model.css_file' => 'required',
            ]);
            $component->model->update();
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$component->model->name} actualizado"]);

            $component->redirectRoute("administrar.v1.usuarios.admin.detalles", ["admin" => $component->model->id]);
        });
    }

    public function setStyle(Component $component)
    {
        $component->style = "";
        $component->styles = array_merge([[
            "key" => "",
            "value" => ""
        ]], Admin::styles());
    }
}
