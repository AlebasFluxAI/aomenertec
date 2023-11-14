<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvailableChannel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;

    public const CHANNEL_WHATSAPP = "whatsapp";
    public const CHANNEL_EMAIL = "email";

    protected $fillable = [
        "channel",
        "enabled",
        "channel_class"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function blink()
    {
        $this->update([
            "enabled" => !$this->enabled
        ]);
    }

    public function enable()
    {
        $this->update([
            "enabled" => true
        ]);
    }

    public function disable()
    {
        $this->update([
            "enabled" => false
        ]);
    }
}
