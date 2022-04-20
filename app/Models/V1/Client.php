<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const MONOPHASIC = 'monophasic';
    public const BIPHASIC = 'biphasic';
    public const TRIPHASIC = 'triphasic';

    protected $fillable = [
        'code',
        'identification',
        'name',
        'email',
        'phone',
        'direction',
        'latitude',
        'longitude',
        'contribution',
        'public_lighting_tax',
        'active_client',
        'network_operator_id',
        'department_id',
        'municipality_id',
        'location_id',
        'client_type_id',
        'subsistence_consumption_id',
        'voltage_level_id',
        'stratum_id',
        'network_topology'];

    public function clientConfiguration(): HasOne
    {
        return $this->hasOne(ClientConfiguration::class);
    }

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function clientType()
    {
        return $this->belongsTo(ClientType::class);
    }

    public function subsistenceConsumption()
    {
        return $this->belongsTo(SubsistenceConsumption::class);
    }

    public function voltageLevel()
    {
        return $this->belongsTo(VoltageLevel::class);
    }

    public function stratum()
    {
        return $this->belongsTo(Stratum::class);
    }

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'equipment_clients', 'client_id', 'equipment_id')
            ->withPivot('current_assigned')
            ->using(EquipmentClient::class);
    }

    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }

    public function microcontrollerData()
    {
        return $this->hasMany(MicrocontrollerData::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'client_supervisors')->withPivot('active');
    }
}
