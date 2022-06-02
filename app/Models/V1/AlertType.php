<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertType extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        "name",
        "unit",
        "value",
    ];

    public function alertHistories()
    {
        return $this->hasMany(AlertHistory::class);
    }
}
