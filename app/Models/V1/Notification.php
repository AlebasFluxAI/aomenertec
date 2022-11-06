<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;


    protected $table = "notifications";

    protected $fillable = [
        'type',
        'notifiable',
        'data',
        'target',
        'read_at',
    ];
}
