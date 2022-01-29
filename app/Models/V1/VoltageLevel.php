<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoltageLevel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
