<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;


    protected $fillable = [
        'id',
        'name',
        'latitude',
        'longitude',
        'pais_id',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }
}
