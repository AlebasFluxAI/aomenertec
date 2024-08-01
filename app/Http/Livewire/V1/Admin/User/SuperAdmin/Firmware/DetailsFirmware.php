<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\V1\Admin\User\SuperAdmin\Firmware\FirmwareDetailsService;
use App\Models\Model\V1\Firmware;
use Livewire\Component;

class DetailsFirmware extends Component
{
    public $model;
    private $FirmwareDetailService;


    public function __construct($id = null)
    {
        $this->FirmwareDetailService = FirmwareDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(Firmware $firmware)
    {
        $this->FirmwareDetailService->mount($this, $firmware);
    }
    public function render()
    {
        return view('livewire.v1.admin.user.super-admin.firmware.details-firmware')
            ->extends('layouts.v1.app');
    }
}
