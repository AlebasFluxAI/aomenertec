<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\AvailableChannelTrait;
use App\Models\Traits\PaginatorTrait;
use App\Scope\ClientEnabledScope;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;
    use AuditableTrait;
    use AvailableChannelTrait;
    use PaginatorTrait;

    public const CLIENT_HEADER = "client_header";

    public const MONTHLY_RATE = "monthly";
    public const DAILY_RATE = "daily";
    public const NONE = "none";

    public const MONOPHASIC = 'monophasic';
    public const BIPHASIC = 'biphasic';
    public const TRIPHASIC = 'triphasic';

    public const PERSON_TYPE_NATURAL = "natural";
    public const PERSON_TYPE_JURIDICAL = "juridical";

    public const CLIENT_STATUS_ENABLED = "enabled";
    public const CLIENT_STATUS_DISABLED = "disabled";

    public const IDENTIFICATION_TYPE_CC = 'CC';
    public const IDENTIFICATION_TYPE_CE = 'CE';
    public const IDENTIFICATION_TYPE_PEP = 'PEP';
    public const IDENTIFICATION_TYPE_PP = 'PP';
    public const IDENTIFICATION_TYPE_NIT = 'NIT';


    public const RESIDENCE_1_41R = "Residencia 1";
    public const RESIDENCE_2_42R = "Residencia 2";
    public const RESIDENCE_3_43R = "Residencia 3";
    public const OFFICIAL_1_410 = "Oficial 1";
    public const OFFICIAL_2_420 = "Oficial 2";
    public const COMMERCIAL_1_41C = "Comercial 1";
    public const COMMERCIAL_2_42C = "Comercial 2";
    public const COMMERCIAL_3_43C = "Comercial 3";
    public const SUSPENDED_R1_R2 = "Suspendidos R1 Y R2";


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
        "alias",
        "indicative",
        "time_zone",
        "status",
        "report_rate",
        "report_variables",
        "activation_requested",
        "monitoring_fee",
        "vaupes_stratification_type"
    ];

    public static function getReportVariableFromId($variable_id)
    {
        $array = collect(config('data-frame.variables'));
        foreach ($array as $element) {
            if ($element["id"] == (int)$variable_id) {
                return $element["display_name"];
            }
        }
    }

    public static function getClientFromSerial($serial)
    {
        $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
        $equipment = $equipment_type->equipment()->whereSerial($serial)
            ->first();
        if ($equipment == null) {
            $equipment_type = EquipmentType::where('type', 'GABINETE')->first();
            $equipment = $equipment_type->equipment()->whereSerial($serial)
                ->first();
            if ($equipment == null) {
                //abort(500, "Error searching equipment");
                return null;
            }
        }
        $client = $equipment->clients()->first();
        if ($client == null) {
            //abort(500, "Error searching client");
            return null;

        }
        return $client;
    }

    public static function vaupesClientStratification()
    {
        return [
            self::RESIDENCE_1_41R => "residence_1_41r",
            self::RESIDENCE_2_42R => "residence_2_42r",
            self::RESIDENCE_3_43R => "residence_3_43r",
            self::OFFICIAL_1_410 => "official_1_410",
            self::OFFICIAL_2_420 => "official_2_420",
            self::COMMERCIAL_1_41C => "commercial_1_41c",
            self::COMMERCIAL_2_42C => "commercial_2_42c",
            self::COMMERCIAL_3_43C => "commercial_3_43c",
            self::SUSPENDED_R1_R2 => "suspended_r1_r2",
        ];
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());

        static::addGlobalScope(new ClientEnabledScope());
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function navigatorDropdownOptions()
    {
        return [
            [
                "title" => "Detalles",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.detail.client",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-search",
                    "tooltip_title" => "Detalles",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_SHOW],
                ],
            ],
            [
                "title" => "Editar",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.edit.client",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-pencil",
                    "tooltip_title" => "Editar",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_EDIT],
                ],
            ],
            [
                "title" => "Configuración",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.settings",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-gear",
                    "tooltip_title" => "Configuración de equipos",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_SETTINGS],
                ],
            ],
            [
                "title" => "Monitoreo",
                "actionable" => [
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_SHOW_MONITORING],
                    "redirect" => [
                        "route" => "v1.admin.client.monitoring",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fa fa-connectdevelop",
                    "tooltip_title" => "Monitoreo",
                    "conditional" => "conditionalMonitoring",
                ],
            ],
            [
                "title" => "Agregar equipos",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.add.equipment",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-computer",
                    "tooltip_title" => "Agregar equipos",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_ADD_EQUIPMENT],
                ],
            ],
            [
                "title" => "Ordenes de trabajo",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.work_orders",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-hammer",
                    "tooltip_title" => "Ordenes de trabajo",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_WORK_ORDER],
                ],
            ],
            [
                "title" => "Historial de cambios",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.change_equipment.historical",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-server",
                    "tooltip_title" => "Historial de cambios de equipo",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_SHOW],
                ],
            ],
            [
                "title" => "Facturas",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.invoicing",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-money-bill",
                    "tooltip_title" => "Facturas",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_SHOW_INVOICING],
                ],
            ],
            [
                "title" => "Alertas",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.add.alerts",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-bell",
                    "tooltip_title" => "Alertas",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_SHOW_ALERTS],
                ],
            ],
            [
                "title" => "On/Off",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.monitoring.control",
                        "binding" => "client",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-toggle-on",
                    "tooltip_title" => "On/Off",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_MONITORING_CONTROL],
                ],
            ],
        ];


    }

    public function getPhonePlusIndicativeAttribute()
    {
        return "(" . $this->indicative . ") " . $this->phone;
    }

    public function enableClient()
    {
        $this->update([
            "status" => Client::CLIENT_STATUS_ENABLED
        ]);
    }

    public function disableClient()
    {
        $this->update([
            "status" => Client::CLIENT_STATUS_DISABLED
        ]);
    }


    public function equipmentChangeHistorical()
    {
        return $this->hasMany(HistoricalClientEquipment::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }


    public function clientSellers()
    {
        return $this->hasMany(ClientSeller::class);
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

    public function currency()
    {
        return $this->admin->configAdmin->coin;
    }

    public function consumption($year, $month)
    {
        if ($this->clientType->type == ClientType::ZIN_CONVENTIONAL) {

            $zniFee = $this->networkOperator->zniFees()->where([
                "voltage_level_id" => $this->voltage_level_id,
                "month" => $month,
                "year" => $year,
            ])->first();
            if (!$zniFee) {
                return $this->networkOperator->zniFees()->create([
                    "voltage_level_id" => $this->voltage_level_id,
                    "month" => $month,
                    "year" => $year,
                ]);
            }
            return $zniFee;


        } else {
            $sinFee = $this->networkOperator->sinFees()->where([
                "voltage_level_id" => $this->voltage_level_id,
                "month" => $month,
                "year" => $year,
            ])->first();
            if (!$sinFee) {
                return $this->networkOperator->sinFees()->create([
                    "voltage_level_id" => $this->voltage_level_id,
                    "month" => $month,
                    "year" => $year,
                ]);
            }
            return $sinFee;

        }
    }

    public function feesDate($month, $year)
    {
        if ($this->clientType->type == ClientType::ZIN_CONVENTIONAL) {

            $zniFee = $this->networkOperator->zniFees()->where([
                "voltage_level_id" => $this->voltage_level_id,
                "month" => $month,
                "year" => $year,
            ])->first();
            if (!$zniFee) {

                return $this->networkOperator->zniFees()->create([
                    "voltage_level_id" => $this->voltage_level_id,
                    "month" => $month,
                    "year" => $year,
                ]);
            }
            return $zniFee;


        } else {
            $sinFee = $this->networkOperator->sinFees()->where([
                "voltage_level_id" => $this->voltage_level_id,
                "month" => $month,
                "year" => $year,
            ])->first();
            if (!$sinFee) {
                return $this->networkOperator->sinFees()->create([
                    "voltage_level_id" => $this->voltage_level_id,
                    "month" => $month,
                    "year" => $year,
                ]);
            }
            return $sinFee;

        }
    }

    public function otherFeesDate($month, $year)
    {
        if ($this->clientType->type == ClientType::ZIN_CONVENTIONAL) {

            $zniFee = $this->networkOperator->zniOtherFees()->where([
                "strata_id" => $this->stratum_id,
                "month" => $month,
                "year" => $year,
            ])->first();
            if (!$zniFee) {

                return $this->networkOperator->zniOtherFees()->create([
                    "strata_id" => $this->stratum_id,
                    "month" => $month,
                    "year" => $year,
                    "tax_type" => SinOtherFee::MONEY_FEE,

                ]);
            }
            return $zniFee;


        } else {
            $sinFee = $this->networkOperator->sinOtherFees()->where([
                "strata_id" => $this->stratum_id,
                "month" => $month,
                "year" => $year,
            ])->first();
            if (!$sinFee) {
                return $this->networkOperator->sinOtherFees()->create([
                    "strata_id" => $this->stratum_id,
                    "month" => $month,
                    "year" => $year,
                    "tax_type" => SinOtherFee::MONEY_FEE,

                ]);
            }
            return $sinFee;

        }
    }


    public function consumptionFeeFlag()
    {

        if ($this->clientType->type == ClientType::ZIN_CONVENTIONAL) {

            $zniFee = $this->networkOperator->zniFees()->where([
                "voltage_level_id" => $this->voltage_level_id
            ])->first();
            if ($zniFee->optional_fee) {
                return true;
            } else {
                return false;
            }

        } else {
            $sinFee = $this->networkOperator->sinFees()->where([
                "voltage_level_id" => $this->voltage_level_id
            ])->first();

            if ($sinFee->optional_fee) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function consumptionFee($year, $month)
    {
        if ($this->clientType->type == ClientType::ZIN_CONVENTIONAL) {

            $zniFee = $this->networkOperator->zniFees()->where([
                "year" => $year,
                "month" => $month,
                "voltage_level_id" => $this->voltage_level_id
            ])->first();
            if (!$zniFee) {
                return 0.0;
            }
            if ($zniFee->optional_fee or $zniFee->optional_fee != 0) {
                return $zniFee->optional_fee;
            } else {
                return $zniFee->unit_cost;
            }

        } else {
            $sinFee = $this->networkOperator->sinFees()->where([
                "year" => $year,
                "month" => $month,
                "voltage_level_id" => $this->voltage_level_id
            ])->first();
            if (!$sinFee) {
                return 0.0;
            }
            if ($sinFee->optional_fee or $sinFee->optional_fee != 0) {
                return $sinFee->optional_fee;
            } else {
                return $sinFee->unit_cost;
            }
        }
    }

    public function stratum()
    {
        return $this->belongsTo(Stratum::class);
    }

    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }

    public function getSerialMeter()
    {
        $meter = $this->equipments()->whereEquipmentTypeId(1)->first();
        return $meter->serial;
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

    public function getSerialGabinete()
    {
        $gabinete = $this->equipments()->whereEquipmentTypeId(1)->first();
        return $gabinete->serial;
    }

    public function lastConsecutiveRecharge()
    {
        $last = $this->recharges()->orderBy("consecutive", 'desc')->first();
        return ($last == null) ? 1 : $last->consecutive;
    }

    public function recharges()
    {
        return $this->hasMany(ClientRecharge::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'client_supervisors')->withPivot('active');
    }

    public function microcontrollerData()
    {
        return $this->hasMany(MicrocontrollerData::class);
    }

    public function handReading()
    {
        return $this->hasMany(MicrocontrollerData::class)->whereManually(true);
    }

    public function hourlyMicrocontrollerData()
    {
        return $this->hasMany(HourlyMicrocontrollerData::class);
    }

    public function dailyMicrocontrollerData()
    {
        return $this->hasMany(DailyMicrocontrollerData::class);
    }

    public function monthlyMicrocontrollerData()
    {
        return $this->hasMany(MonthlyMicrocontrollerData::class);
    }

    public function annualMicrocontrollerData()
    {
        return $this->hasMany(AnnualMicrocontrollerData::class);
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

    public function addresses()
    {
        return $this->hasMany(ClientAddress::class);
    }

    public function address()
    {
        return $this->hasOne(ClientAddress::class);
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
