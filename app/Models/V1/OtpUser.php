<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpUser extends Model
{
    use HasFactory;
    use PaginatorTrait;


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
