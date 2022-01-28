<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stratum extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
