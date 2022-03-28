<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use Livewire\Component;

class AdminEditService extends Singleton
{
    public function mount(Component $component, Admin $model)
    {
        $component->fill([
            'model' => $model,
            'name' => $model->name,
            'last_name' => $model->last_name,
            'phone' => $model->phone,
            'email' => $model->email,
            'address' => $model->address,
            'nit' => $model->nit,
            'password' => $model->password,
            'identification' => $model->identification,
            'style' => $model->css_file,

        ]);
    }


    public function submitForm(Component $component)
    {
        if ($component->icon) {
            $image = $component->icon;
            $component->model->icon->setDataImage($image);
            $component->model->icon->name = $image->getClientOriginalName();
            $component->model->icon->update();
        }
        $component->model->fill($this->mapper($component));
        $component->model->update();
        $component->redirectRoute("administrar.v1.usuarios.admin.detalles", ["admin" => $component->model->id]);
    }

    private function mapper(Component $component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "address" => $component->address,
            "nit" => $component->nit,
            "identification" => $component->identification,
            "css_file" => $component->style
        ];
    }
}
