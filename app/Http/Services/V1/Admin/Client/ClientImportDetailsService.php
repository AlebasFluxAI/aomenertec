<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
use App\Models\V1\Import;
use App\Models\V1\ImportItem;
use Livewire\Component;

class ClientImportDetailsService extends Singleton
{
    use ClientServiceTrait;

    public function mount(Component $component, Import $import)
    {
        $component->model = $import;
    }

    public function completedStatus($importItemId)
    {
        return ImportItem::find($importItemId)->status != Import::STATUS_COMPLETED;
    }

    public function getData(Component $component)
    {
        return $component->model->items()->paginate();
    }
}
