<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PqrState extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }
}
