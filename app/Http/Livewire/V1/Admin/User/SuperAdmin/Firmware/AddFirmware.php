<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\V1\Admin\User\SuperAdmin\Firmware\FirmwareAddService;
use Livewire\Component;

class AddFirmware extends Component
{
    public $model;
    public $message;
    public $file;
    protected $rules = [
        'model.name' => 'required|min:6',
        'model.version' => 'required|min:6',
        'model.description' => 'required|min:6',
    ];

    private $FirmwareAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->FirmwareAddService = FirmwareAddService::getInstance();
    }

    public function updated($propertyName)
    {
        $this->FirmwareAddService->updated($this, $propertyName);
    }

    public function submitForm()
    {
        $this->FirmwareAddService->submitForm($this);
    }
    public function render()
    {
        return view('livewire.v1.admin.user.super-admin.firmware.add-firmware')
            ->extends('layouts.v1.app');
    }
}
