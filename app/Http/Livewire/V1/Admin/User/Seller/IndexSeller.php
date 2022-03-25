<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;


use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\User\Seller\SellerIndexService;
use App\Models\V1\Seller;
use App\Models\V1\Supervisor;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class IndexSeller extends Component
{
    use WithPagination;


    private $indexSellerService;

    public function __construct($id = null)
    {
        $this->indexSellerService = SellerIndexService::getInstance();
        parent::__construct($id);
    }


    public function edit($id)
    {
        $this->indexSellerService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexSellerService->delete($this, $id);

    }

    public function details($id)
    {
        $this->indexSellerService->details($this, $id);

    }

    public function render()
    {
        return view('livewire.v1.admin.user.seller.index-seller',
            [
                "data" => $this->getData()
            ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexSellerService->getData();
    }

}
