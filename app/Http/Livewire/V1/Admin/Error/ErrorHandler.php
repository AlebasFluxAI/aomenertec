<?php

namespace App\Http\Livewire\V1\Admin\Error;

use App\Http\Services\V1\Admin\User\AddUserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

use function view;

class ErrorHandler extends Component
{

    public function render()
    {
        return view('livewire.v1.admin.error.error-handler');
    }
}
