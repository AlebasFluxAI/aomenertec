<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\ImageableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdAscScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PqrMessage extends Model
{
    use ImageableTrait;
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const SENDER_TYPE_USER = "user";
    public const SENDER_TYPE_NETWORK_OPERATOR = "network_operator";
    public const SENDER_TYPE_SUPERVISOR = "supervisor";
    public const SENDER_TYPE_CLIENT = "cient";

    public const MESSAGE_TYPE_REGULAR = "regular";
    public const MESSAGE_TYPE_CLOSER = "closer";

    protected $fillable = [
        "message",
        "sender_type",
        "sent_by",
        "pqr_id",
        "type"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdAscScope());
    }


    public function attach()
    {
        return $this->morphOne(Image::class, "imageable");
    }


    public function pqr()
    {
        return $this->belongsTo(Pqr::class);
    }

    public function sender()
    {
        return match ($this->sender_type) {
            self::SENDER_TYPE_CLIENT => Client::find($this->sent_by),
            self::SENDER_TYPE_USER => User::find($this->sent_by),
            self::SENDER_TYPE_SUPERVISOR => Supervisor::find($this->sent_by),
            self::SENDER_TYPE_NETWORK_OPERATOR => NetworkOperator::find($this->sent_by),
            default => User::find($this->sent_by),
        };
    }
}
