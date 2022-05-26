<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
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

    public const PERSON_TYPE_NATURAL = "natural";
    public const PERSON_TYPE_JURIDICAL = "juridical";

    public const IDENTIFICATION_TYPE_CC = 'CC';
    public const IDENTIFICATION_TYPE_CE = 'CE';
    public const IDENTIFICATION_TYPE_PEP = 'PEP';
    public const IDENTIFICATION_TYPE_PP = 'PP';
    public const IDENTIFICATION_TYPE_NIT = 'NIT';

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
        'network_topology',
        "person_type",
        "identification_type",
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

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

    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }

    public function microcontrollerData()
    {
        return $this->hasMany(MicrocontrollerData::class)->orderBy('source_timestamp', 'desc');
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'client_supervisors')->withPivot('active');
    }

    public function hourlyMicrocontrollerData()
    {
        return $this->hasMany(HourlyMicrocontrollerData::class)->orderBy('created_at', 'desc');
    }

    public function dailyMicrocontrollerData()
    {
        return $this->hasMany(DailyMicrocontrollerData::class)->orderBy('created_at', 'desc');
    }

    public function monthlyMicrocontrollerData()
    {
        return $this->hasMany(MonthlyMicrocontrollerData::class)->orderBy('created_at', 'desc');
    }

    public function annualMicrocontrollerData()
    {
        return $this->hasMany(AnnualMicrocontrollerData::class)->orderBy('created_at', 'desc');
    }

    public function technician()
    {
        return $this->hasMany(ClientTechnician::class)->latest();
    }

    public function equipmentsAsKeyValue()
    {
        return (($this->equipments()
            ->get()->map(function ($data) {
                return [
                    "key" => $data->id . "-" . $data->name,
                    "value" => $data->id,
                ];
            }))->toArray()
        );
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }


}
