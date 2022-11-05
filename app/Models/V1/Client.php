<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\AvailableChannelTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;
    use AuditableTrait;
    use AvailableChannelTrait;

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
        'last_name',
        'email',
        'phone',
        'direction',
        'latitude',
        'longitude',
        'contribution',
        'public_lighting_tax',
        'active_client',
        'network_operator_id',
        'location_id',
        'client_type_id',
        'subsistence_consumption_id',
        'voltage_level_id',
        'stratum_id',
        'network_topology',
        "person_type",
        "identification_type",
        "has_telemetry",
        "admin_id",
        "alias"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function equipmentChangeHistorical()
    {
        return $this->hasMany(HistoricalClientEquipment::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function clientConfiguration(): HasOne
    {
        return $this->hasOne(ClientConfiguration::class);
    }

    public function clientAlertConfiguration()
    {
        return $this->hasMany(ClientAlertConfiguration::class)->orderBy('flag_id');
    }

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }


    public function clientType()
    {
        return $this->belongsTo(ClientType::class);
    }

    public function subsistenceConsumption()
    {
        return $this->belongsTo(SubsistenceConsumption::class);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function voltageLevel()
    {
        return $this->belongsTo(VoltageLevel::class);
    }

    public function billingInformation()
    {
        return $this->hasMany(BillingInformation::class);
    }


    public function stratum()
    {
        return $this->belongsTo(Stratum::class);
    }

    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'client_supervisors')->withPivot('active');
    }

    public function microcontrollerData()
    {
        return $this->hasMany(MicrocontrollerData::class);
    }

    public function hourlyMicrocontrollerData()
    {
        return $this->hasMany(HourlyMicrocontrollerData::class)->orderBy('microcontroller_data_id', 'desc');
    }

    public function dailyMicrocontrollerData()
    {
        return $this->hasMany(DailyMicrocontrollerData::class)->orderBy('microcontroller_data_id', 'desc');
    }

    public function monthlyMicrocontrollerData()
    {
        return $this->hasMany(MonthlyMicrocontrollerData::class)->orderBy('microcontroller_data_id', 'desc');
    }

    public function annualMicrocontrollerData()
    {
        return $this->hasMany(AnnualMicrocontrollerData::class)->orderBy('microcontroller_data_id', 'desc');
    }

    public function technician()
    {
        return $this->hasMany(ClientTechnician::class)->latest();
    }

    public function clientTechnician()
    {
        return $this->belongsToMany(
            Technician::class,
            'client_technicians',
            'client_id',
            'technician_id'
        );
    }

    public function equipmentsAsKeyValue()
    {
        return (($this->equipments()
            ->get()->map(function ($data) {
                return [
                    "key" => $data->id . "-" . ($data->name ?: " Sin nombre ") . "-" . $data->serial,
                    "value" => $data->id,
                ];
            }))->toArray()
        );
    }

    public function equipments()
    {
        return $this->belongsToMany(
            Equipment::class,
            'equipment_clients',
            'client_id',
            'equipment_id'
        )
            ->where("current_assigned", true)
            ->whereNull("equipment_clients.deleted_at");
    }

    public function addresses()
    {
        return $this->hasMany(ClientAddress::class);
    }

    public function digitalOutputs()
    {
        return $this->hasMany(ClientDigitalOutput::class)->orderBy('number');
    }

    public function alertConfigurationDigitalOutputs()
    {
        return $this->hasMany(ClientDigitalOutputAlertConfiguration::class);
    }

    public function clientAlerts()
    {
        return $this->hasMany(ClientAlert::class);
    }

    public function stopUnpackClient()
    {
        return $this->hasOne(StopUnpackDataClient::class);
    }
}
