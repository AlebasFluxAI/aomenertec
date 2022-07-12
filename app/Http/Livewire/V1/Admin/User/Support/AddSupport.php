<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\User\Support\SupportAddService;
use App\Models\Traits\AddUserFormTrait;
use App\Models\Traits\PassTrait;
use App\Models\Traits\ValidateUserFormTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddSupport extends Component
{
    use ValidateUserFormTrait;
    use AddUserFormTrait;
    use PassTrait;

    public $message;
    public $picked;
    public $network_operators;
    public $network_operator;
    public $network_operator_id;


    private $superSupportAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superSupportAddService = SupportAddService::getInstance();
    }

    public function assignNetworkOperator($network_operator)
    {
        $this->superSupportAddService->assignNetworkOperator($this, $network_operator);
    }

    public function updatedNetworkOperator()
    {
        $this->superSupportAddService->updatedNetworkOperator($this);
    }

    public function mount()
    {
        $this->superSupportAddService->mount($this);
    }

    public function submitForm()
    {
        $this->superSupportAddService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.support.add-support')
            ->extends('layouts.v1.app');
    }
}
