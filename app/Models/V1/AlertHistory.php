<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
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

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function microcontrollerData()
    {
        return $this->belongsTo(MicrocontrollerData::class);
    }
}
