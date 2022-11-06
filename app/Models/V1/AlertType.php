<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertType extends Model
{
    use HasFactory;
    use AuditableTrait;
    use SoftDeletes;
    use PaginatorTrait;


    protected $fillable = [
        "name",
        "unit",
        "value",
    ];

    public function alertHistories()
    {
        return $this->hasMany(AlertHistory::class);
    }
}
