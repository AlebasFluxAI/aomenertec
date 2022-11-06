<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;


    protected $fillable = [
        'id',
        'location_type_id',
        'name',
        'municipality_id'
    ];

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
