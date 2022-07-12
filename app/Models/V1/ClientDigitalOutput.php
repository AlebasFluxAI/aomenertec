<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDigitalOutput extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const AUTOMATIC = 'automatic';
    public const MANUAL = 'manual';

    public const NC = 'nc';
    public const NO = 'no';


    protected $fillable = [
        'client_id',
        'number',
        'name',
        'status',
        'control_type',
    ];
}
