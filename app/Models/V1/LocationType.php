<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;


    protected $fillable = [
        'type',
        'acronym',
    ];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
