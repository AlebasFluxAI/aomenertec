<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuditFieldMigration extends Singleton
{
    public static function auditoryField(&$table)
    {
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('created_by')->on('users')->references('id');
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->foreign('updated_by')->on('users')->references('id');
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->foreign('deleted_by')->on('users')->references('id');
    }
}
