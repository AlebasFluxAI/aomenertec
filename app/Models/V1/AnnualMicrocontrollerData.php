<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnualMicrocontrollerData extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'year',
        'month',
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
