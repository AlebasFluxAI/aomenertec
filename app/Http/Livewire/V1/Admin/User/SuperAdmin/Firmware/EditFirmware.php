<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\V1\Admin\User\SuperAdmin\Firmware\FirmwareEditService;
use App\Models\Model\V1\Firmware;
use Livewire\Component;

class EditFirmware extends Component
{
    public $model;
    public $file;

    protected $rules = [
        'model.description' => 'required|min:6',
        'model.name' => 'required|min:6',
        'model.version' => 'required|min:6'
    ];
    private $firmwareEditService;

    public function __construct($id = null)
    {
        $this->firmwareEditService = FirmwareEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(Firmware $firmware)
    {
        $this->firmwareEditService->mount($this, $firmware);
    }


    public function submitForm()
    {
        $this->firmwareEditService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.super-admin.firmware.edit-firmware')
            ->extends('layouts.v1.app');
    }
}
