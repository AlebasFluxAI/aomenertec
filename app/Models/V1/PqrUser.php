<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PqrUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const STATUS_ENABLED = "enabled";
    public const STATUS_DISABLED = "disabled";
    protected $fillable = [
        "user_id",
        "pqr_id",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pqr()
    {
        return $this->belongsTo(Pqr::class);
    }
}
