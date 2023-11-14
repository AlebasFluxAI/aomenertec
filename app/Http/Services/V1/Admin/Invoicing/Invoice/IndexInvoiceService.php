<?php

namespace App\Http\Services\V1\Admin\Invoicing\Invoice;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Admin;
use App\Models\V1\Invoice;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Livewire\Component;

class IndexInvoiceService extends Singleton
{

    public function mount(Component $component)
    {
        $component->filterCol = "type";
        $component->filter = Invoice::TYPE_PLATFORM_USAGE;
    }

    public function setFilter(Component $component, $filterValue)
    {
        $component->filterCol = "type";
        $component->filter = $filterValue;

    }


    public function getData(Component $component)
    {
        $model = User::getUserModel();
        if ($model::class == NetworkOperator::class) {
            if ($component->filter) {
                return Invoice::whereNetworkOperatorId($model->id)->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return Invoice::whereNetworkOperatorId($model->id)->pagination();
        }
        if ($model::class == Admin::class) {
            if ($component->filter) {
                return Invoice::whereAdminId($model->id)->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return Invoice::whereAdminId($model->id)->pagination();
        }
        if ($component->filter) {
            return Invoice::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }

        return Invoice::pagination();
    }


}
