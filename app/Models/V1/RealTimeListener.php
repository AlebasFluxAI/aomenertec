<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealTimeListener extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "equipment_id"
    ];
}
