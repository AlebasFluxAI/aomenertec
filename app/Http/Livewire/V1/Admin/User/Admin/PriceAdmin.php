<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\PriceAdminService;
use App\Models\V1\Admin;
use Livewire\Component;

class PriceAdmin extends Component
{
    public $prices;

    private $priceAdminService;

    protected $rules = [
        'prices.*.client_type_id' => 'required',
        'prices.*.admin_id' => 'required',
        'prices.*.value' => 'required',
        'prices.*.coin' => 'required',
    ];

    public function __construct($id = null)
    {
        $this->priceAdminService= PriceAdminService::getInstance();
        parent::__construct($id);
    }

    public function mount(Admin $admin)
    {
        $this->priceAdminService->mount($this, $admin);
    }

    public function submitForm()
    {
        $this->priceAdminService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.price-admin')
            ->extends('layouts.v1.app');
    }
}
