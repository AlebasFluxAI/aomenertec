<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubsistenceConsumption extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
