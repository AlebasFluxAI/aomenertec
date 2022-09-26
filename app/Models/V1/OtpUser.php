<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "otp",
        "enabled"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
