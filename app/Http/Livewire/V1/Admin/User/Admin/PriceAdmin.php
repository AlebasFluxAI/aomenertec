<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\PriceAdminService;
use App\Models\V1\Admin;
use App\Models\V1\AdminConfiguration;
use Livewire\Component;

class PriceAdmin extends Component
{
    public $prices;
    public $config;
    public $client_types;
    public $coins;
    public $admin_client_types;
    public $model;
    public $frame_types;
    public $frame_type;

    private $priceAdminService;

    protected $rules = [

        'prices.*.value' => 'required',
        'config.min_clients' => 'required',
        'config.min_value' => 'required',
        'config.coin' => 'required',
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

    public function submitFormPrice()
    {
        $this->priceAdminService->submitFormPrice($this);
    }

    public function submitFormConfiguration()
    {
        $this->priceAdminService->submitFormConfiguration($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.price-admin')
            ->extends('layouts.v1.app');
    }
}
