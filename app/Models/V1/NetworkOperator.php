<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkOperator extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function sellers()
    {
        return $this->hasMany(Seller::class);
    }
    public function technicians()
    {
        return $this->hasMany(Technician::class);
    }
    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }
}
