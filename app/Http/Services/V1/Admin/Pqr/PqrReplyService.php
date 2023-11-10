<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\PqrTypesTrait;
use Livewire\Component;

class PqrReplyService extends Singleton
{
    use PqrTypesTrait;

    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $messages = $component->messages;
    }
}
