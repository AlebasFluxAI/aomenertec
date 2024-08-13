<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\V1\Admin\User\SuperAdmin\Firmware\FirmwareIndexService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;

class IndexFirmware extends Component
{
    use WithPagination;
    use FilterTrait;
    public $model;

    private $indexFirmwareService;
    public function __construct($id = null)
    {
        $this->indexFirmwareService = FirmwareIndexService::getInstance();
        parent::__construct($id);
    }

    public function edit($id)
    {
        $this->indexFirmwareService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexFirmwareService->delete($this, $id);
    }

    public function details($id)
    {
        $this->indexFirmwareService->details($this, $id);
    }
    public function downloadFile($id)
    {
        $this->indexFirmwareService->downloadFile($this, $id);
    }
    public function otaUpload($id)
    {
        $this->indexFirmwareService->otaUpload($this, $id);
    }

    public function getData()
    {
        return  $this->indexFirmwareService->getData($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.super-admin.firmware.index-firmware',
        ["data" => $this->getData()])->extends('layouts.v1.app');
    }
}
