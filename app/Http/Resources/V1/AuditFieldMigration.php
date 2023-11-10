<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;

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
