<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Import extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_COMPLETED = "completed";
    public const STATUS_PROCESSING = "processing";
    public const STATUS_ERROR = "error";
    public const STATUS_PENDING = "pending";

    public const TYPE_CLIENT = "client";
    protected $fillable = [
        "name",
        "status",
        "url",
        "type",
        "auditable_type",
        "auditable_id",
        "file_name"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function items()
    {
        return $this->hasMany(ImportItem::class);
    }

}
