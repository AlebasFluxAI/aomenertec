<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyMicrocontrollerData extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'year',
        'month',
        'day',
        'hour',
        'client_id',
        'microcontroller_data_id',
        "interval_real_consumption",
        "interval_reactive_capacitive_consumption",
        "interval_reactive_inductive_consumption",
        "penalizable_reactive_capacitive_consumption",
        "penalizable_reactive_inductive_consumption",
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function microcontrollerData()
    {
        return $this->belongsTo(MicrocontrollerData::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
