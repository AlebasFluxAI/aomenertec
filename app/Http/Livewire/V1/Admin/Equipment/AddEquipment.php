<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class AddEquipment extends Component
{
    public $equipmentSerial;
    public $serial;
    public $description;
    public $equipmentName;
    public $equipmentDescription;
    public $equipmentTypeId;
    public $equipmentTypes;
    public $picked;

    private $addEquipmentService;

    protected $rules = [
        'equipmentName' => 'required|min:2',
        'equipmentSerial'=> 'unique:equipments,serial'
    ];

    public function __construct($id = null)
    {
        $this->addEquipmentService = EquipmentAddService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        $this->fill([
            'equipmentName'=>null,
            'equipmentDescription'=>null,
            'equipmentSerial'=>null,
            'equipmentTypeId' => null,
            'equipmentTypes'=>[],
            'picked'=>false,
        ]);
    }

    public function updatedEquipmentTypeId()
    {
        $this->picked = false;
        $this->equipmentTypes=EquipmentType::where('id', 'like', "%".$this->equipmentTypeId."%")->limit(3)->get();
    }

    public function setEquipmentType($equipmentType)
    {
        $this->picked=true;
        $equipmentType=json_decode($equipmentType);
        $this->equipmentTypeId=$equipmentType->id;
    }

    public function updatedSelectedState($state)
    {
        $this->addEquipmentService->updatedSelectedState($this, $state);
    }

    public function submit()
    {
        $this->addEquipmentService->submitForm($this);
    }
    public function updatingSearch()
    {
        $this->equipment_types=EquipmentType::whereId($this->equipment_type_id)->paginate(15);
    }
    public function render()
    {
        return view('livewire.administrar.v1.add-equipment')
            ->extends('layouts.v1.app');
    }
}
