<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampleInterval extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "interval",
        "equipments_id"
    ];


    public function equipment()
    {
        return $this->belongsTo(Equipment::class, "equipments_id");
    }
}
