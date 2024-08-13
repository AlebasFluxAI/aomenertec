<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\V1\Admin\User\SuperAdmin\Firmware\FirmwareEditService;
use App\Http\Services\V1\Admin\User\SuperAdmin\Firmware\OtaUpdateService;
use App\Models\Model\V1\Firmware;
use Livewire\Component;

class OtaUpdate extends Component
{
    public $model;
    public $progress;
    public $status;
    public $total_frames;
    public $frame;
    public $meters;
    public $meter;
    public $meter_picked;
    public $meter_id;
    public $message_meter;
    private $otaUpdateService;

    public function __construct($id = null)
    {
        $this->otaUpdateService = OtaUpdateService::getInstance();
        parent::__construct($id);
    }
    public function getListeners()
    {
        return [
            "echo:data-ota-upload." . $this->model->id . ",.dataEventSetProgress" => 'setProgress',
        ];
    }
    public function mount(Firmware $firmware)
    {
        $this->otaUpdateService->mount($this, $firmware);
    }
    public function updatedMeter()
    {
        $this->otaUpdateService->updatedMeter($this);
    }
    public function assignMeter($meter)
    {
        $this->otaUpdateService->assignMeter($this, $meter);
    }

    public function submitForm()
    {
        $this->otaUpdateService->submitForm($this);
    }
    public function setProgress($progress)
    {
        $this->progress = $progress['progress'];
        if($this->progress == 100){
            $this->status = false;
            $this->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Carga completada"]);

        }

    }
    public function render()
    {
        return view('livewire.v1.admin.user.super-admin.firmware.ota-update')
            ->extends('layouts.v1.app');
    }
}
