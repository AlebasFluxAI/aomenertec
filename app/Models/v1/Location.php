<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }
    public function locationType()
    {
        return $this->belongsTo(LocationType::class);
    }
}
