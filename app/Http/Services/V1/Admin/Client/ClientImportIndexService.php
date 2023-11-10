<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
use App\Models\V1\Admin;
use App\Models\V1\Import;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;

class ClientImportIndexService extends Singleton
{
    use ClientServiceTrait;

    public function getData()
    {
        $admin = Auth::user()->getAdmin();
        if (User::getUserModel()::class == Admin::class) {
            return Import::whereIn("auditable_id", array_merge($admin->networkOperators()->pluck('id')->toArray(), [$admin->id]))->paginate();
        }
        if (User::getUserModel()::class == NetworkOperator::class) {
            return Import::whereAuditableId(Auth::user()->id)->paginate();
        }
        if (User::getUserModel()::class == SuperAdmin::class) {

            return Import::paginate();
        }
        return [];

    }

}
