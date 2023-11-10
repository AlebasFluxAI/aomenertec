<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\PriceAdminService;
use App\Models\V1\Admin;
use Livewire\Component;

class PriceAdmin extends Component
{
    public $prices;
    public $config;
    public $months;
    public $month;
    public $client_types;
    public $notification_types;
    public $coins;
    public $admin_client_types;
    public $admin_notification_types;
    public $model;
    public $channels;
    public $tab_permissions;
    public $invoicing_day;
    public $annually_client_cost;
    public $annually_client_invoicing_month;
    protected $rules = [

        'prices.*.value' => 'required',
        'config.min_clients' => 'required',
        'config.min_value' => 'required',
        'config.coin' => 'required',
    ];
    private $priceAdminService;

    public function __construct($id = null)
    {
        $this->priceAdminService = PriceAdminService::getInstance();
        parent::__construct($id);
    }

    public function mount(Admin $admin)
    {
        $this->priceAdminService->mount($this, $admin);
    }

    public function submitFormInvoicing()
    {
        $this->priceAdminService->submitFormInvoicing($this);
    }

    public function submitFormPrice()
    {
        $this->priceAdminService->submitFormPrice($this);
    }

    public function submitFormConfiguration()
    {
        $this->priceAdminService->submitFormConfiguration($this);
    }

    public function blinkChannel($channel)
    {
        $this->priceAdminService->blinkChannel($this, $channel);
    }

    public function blinkTabPermission($tabPermission)
    {
        $this->priceAdminService->blinkTabPermission($this, $tabPermission);
    }

    public function submitAnnuallyForm()
    {
        $this->priceAdminService->submitAnnuallyForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.price-admin')
            ->extends('layouts.v1.app');
    }
}
