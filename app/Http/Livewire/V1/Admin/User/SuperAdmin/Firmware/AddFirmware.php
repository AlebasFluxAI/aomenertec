<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\V1\Admin\User\SuperAdmin\Firmware\FirmwareAddService;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddFirmware extends Component
{
    use WithFileUploads;

    public $model;
    public $message;
    public $file;
    protected $rules = [
        'model.name' => 'required|min:6',
        'model.version' => 'required',
        'model.description' => 'required|min:6',
        'file' => 'required|mimetypes:application/octet-stream,application/x-dosexec|max:2048'
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
