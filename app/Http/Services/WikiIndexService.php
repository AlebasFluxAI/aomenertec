<?php

namespace App\Http\Services;

use App\Http\Resources\V1\ToastEvent;
use App\Models\V1\WikiTree;
use Livewire\Component;

class WikiIndexService extends Singleton
{
    public function mount(Component $component)
    {
        $component->wikies = WikiTree::get();
        $wiki = $component->wikies->first();
        if (!$wiki) {
            return;
        }
        $component->content = $wiki->content;
        $component->title = $wiki->title;
    }

    public function showInput(Component $component, $id)
    {
        $wiki = WikiTree::find($id);
        $component->content = $wiki->content;
        $component->title = $wiki->title;
        ToastEvent::launchToast($component, "show", "success", "Entrada cargada");
    }
}
