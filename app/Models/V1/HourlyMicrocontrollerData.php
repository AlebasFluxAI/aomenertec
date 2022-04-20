<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HourlyMicrocontrollerData extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hourly_microcontroller_data';

    protected $fillable = [
        'year',
        'month',
        'day',
        'hour',
        'minute',
        'client_id',
        'microcontroller_data_id'
    ];

    public function microcontrollerData()
    {
        return $this->belongsTo(MicrocontrollerData::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
