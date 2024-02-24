<?php

namespace App\Models\V1\Api;

use App\Models\V1\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiKey extends Model
{

    use SoftDeletes;


    public const  STATUS_ENABLED = "enabled";
    public const  STATUS_DISABLED = "disabled";
    public const API_HEADER = "x-api-key";

    protected $fillable = [
        "api_key",
        "expiration",
        "user_id",
        "status",
        "end_point_notification",
        "security_header_value",
        "security_header_key",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid()
    {
        return ($this->validateExpiration() && $this->isEnabled());
    }

    public function validateExpiration(): bool
    {
        return ($this->expiration > Carbon::now());
    }

    public function isEnabled(): bool
    {
        return ($this->status == ApiKey::STATUS_ENABLED);
    }
}
