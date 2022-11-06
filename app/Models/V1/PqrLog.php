<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PqrLog extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const ACTIVITY_TYPE_CHANGE_LEVEL = "change_level";
    public const ACTIVITY_TYPE_CLOSE_TICKET = "close_ticket";
    public const ACTIVITY_TYPE_OPEN_TICKET = "open_ticket";
    public const ACTIVITY_TYPE_REOPEN_TICKET = "reopen_ticket";
    public const ACTIVITY_TYPE_CHANGE_STATUS = "change_status";

    protected $fillable = [
        "pqr_id",
        "activity_type",
        "before",
        "after",
        "created_by",
        "updated_by"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function pqr()
    {
        return $this->belongsTo(Pqr::class);
    }
}
