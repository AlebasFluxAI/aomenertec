<?php

namespace App\Http\Livewire\V1\Admin\User;

use App\Http\Services\V1\Admin\User\TabPermissionService;
use Livewire\Component;
use function view;

class TabPermission extends Component
{

    public $tab_permissions;
    public $model;
    public $model_class;
    private $tabPermissionService;

    public function __construct()
    {
        parent::__construct();
        $this->tabPermissionService = TabPermissionService::getInstance();
    }

    public function mount()
    {
        $this->tabPermissionService->mount($this);
    }

    public function clients($tabPermissionId)
    {
        return $this->tabPermissionService->clients($this, $tabPermissionId);
    }

    public function enabled($tabPermissionId)
    {
        return $this->tabPermissionService->enabled($this, $tabPermissionId);

    }

    public function blinkTabPermission($permissionId)
    {
        $this->tabPermissionService->blinkTabPermission($this, $permissionId);
    }

    public function render()
    {
        return view("livewire.v1.admin.user.tab-permissions")
            ->extends('layouts.v1.app');
    }
}
