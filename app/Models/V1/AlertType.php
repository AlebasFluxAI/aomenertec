<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
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

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function alertHistories()
    {
        return $this->hasMany(AlertHistory::class);
    }
}
