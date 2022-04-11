<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

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
