<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertHistory extends Model
{
    use HasFactory;
    use AuditableTrait;
    use SoftDeletes;
    use PaginatorTrait;

    protected $fillable = [
        'microcontroller_data_id',
        'flag_index',
        'value'
    ];

    public function microcontrollerData()
    {
        return $this->belongsTo(MicrocontrollerData::class);
    }
}
