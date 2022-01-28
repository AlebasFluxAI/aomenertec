<?php

namespace App\Models\v1;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function networkOperator(){
        return $this->belongsTo(NetworkOperator::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function municipality(){
        return $this->belongsTo(Municipality::class);
    }
    public function location(){
        return $this->belongsTo(Location::class);
    }
    public function clientType(){
        return $this->belongsTo(ClientType::class);
    }
    public function subsistenceConsumption(){
        return $this->belongsTo(SubsistenceConsumption::class);
    }
    public function voltageLevel(){
        return $this->belongsTo(VoltageLevel::class);
    }
    public function stratum(){
        return $this->belongsTo(Stratum::class);
    }
    public function networkTopology(){
        return $this->belongsTo(NetworkTopology::class);
    }
    public function equipments(){
        return $this->belongsToMany(Equipment::class, 'equipments_per_clients');
    }
    public function pqrs(){
        return $this->hasMany(Pqr::class);
    }

}
