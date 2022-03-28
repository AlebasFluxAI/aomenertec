<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientSupervisor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supervisor_id',
        'client_id',
        'active'
    ];
}
