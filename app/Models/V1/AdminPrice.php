<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminPrice extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const COP = 'cop';
    public const USD = 'usd';
}
