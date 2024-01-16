<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\WikiTree;
use Livewire\Component;

class WikiInputConfigService extends Singleton
{
    public function mount(Component $component)
    {
        $component->wikies = WikiTree::get();
    }

    public function submitForm(Component $component)
    {

        if (!$component->wiki_id) {
            WikiTree::create([
                "content" => $component->content ?? "Sin contenidp",
                "title" => $component->title,
                "parent_id" => $component->parent == "" ? null : $component->parent,
            ]);
            $component->redirect(route("configuracion.v1.wiki.entradas"));
            ToastEvent::launchToast($component, "show", "success", "Nueva entrada creada exitosamente");

        } else {
            $wiki = WikiTree::find($component->wiki_id);
            $wiki->update([
                "content" => $component->content,
                "title" => $component->title,
                "parent_id" => $component->parent == "" ? null : $component->parent,
            ]);
            ToastEvent::launchToast($component, "show", "success", "Entrada editada exitosamente");

        }

    }

    public function showInput(Component $component, $id)
    {
        $wiki = WikiTree::find($id);
        $component->content = $wiki->content;
        $component->title = $wiki->title;
        $component->parent = $wiki->parent_id;
        $component->wiki_id = $wiki->id;
        $component->dispatchBrowserEvent('contentChanged', ['content' => $wiki->content]);
        ToastEvent::launchToast($component, "show", "success", "Entrada cargada");

    }

}
