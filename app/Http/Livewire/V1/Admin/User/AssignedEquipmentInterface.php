<?php

namespace App\Http\Livewire\V1\Admin\User;

interface AssignedEquipmentInterface
{
    public function getPageTitle();

    public function getNavOptions();

    public function deleteEquipmentAssigned($id);

    public function updatedEquipmentTypeId();

    public function updatedEquipmentFilter();

    public function updatedSelectedAll();

    public function getCardTitle();
}
