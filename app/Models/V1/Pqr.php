<?php

namespace App\Models\V1;

use App\Models\Traits\ImageableTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pqr extends Model
{
    use HasFactory;
    use ImageableTrait;
    use SoftDeletes;

    public const STATUS_CREATED = 'created';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    public const PQR_TYPE_BILLING = "type_billing";
    public const PQR_TYPE_PLATFORM = "type_platform";
    public const PQR_TYPE_TECHNICIAN = "type_technicians";

    public const PQR_SUB_TYPE_OVERRUN = "sub_type_overrun";
    public const PQR_SUB_TYPE_INVOICING = "sub_type_invoicing";
    public const PQR_SUB_TYPE_PAYMENT_AGREE = "sub_type_payment_agree";

    public const PQR_SUB_TYPE_PLATFORM_ADMIN = "sub_type_platform_admin";

    public const PQR_SUB_TYPE_ERROR = "sub_type_error";

    public const PQR_SEVERITY_LOW = "severity_low";
    public const PQR_SEVERITY_MEDIUM = "severity_medium";
    public const PQR_SEVERITY_HIGH = "severity_high";

    public const PQR_LEVEL_1 = "level_1";
    public const PQR_LEVEL_2 = "level_2";


    protected $fillable = [
        'detail',
        'equipment_id',
        'pqr_type_id',
        'network_operator_id',
        'technician_id',
        'user_id',
        'client_id',
        'support_id',
        'status',
        'subject',
        'description',
        'level',
        'type',
        'sub_type',
        'severity',
        'contact_name',
        'contact_phone',
        'contact_identification',
        'client_code',
        "code",
        "supervisor_id",
        "change_equipment",
        "has_equipment_changed"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function support()
    {
        return $this->belongsTo(Support::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }


    public function messages()
    {
        return $this->hasMany(PqrMessage::class);
    }

    public function pqrLogs()
    {
        return $this->hasMany(PqrLog::class);
    }

    public function attach()
    {
        return $this->morphOne(Image::class, "imageable");
    }

    public function setEquipmentChanged()
    {
        $this->update([
            "has_equipment_changed" => true,
            "change_equipment" => false]);
    }

    public function pqrUsers()
    {
        return $this->hasMany(PqrUser::class);
    }

    public function logs()
    {
        return $this->hasMany(PqrLog::class);
    }

    public function senderType()
    {
        if ($this->networkOperator) {
            return "Usuario operador de red";
        }
        if ($this->supervisor) {
            return "Usuario supervisor";
        }
        return "Cliente";
    }

    public function equipmentChangeHistorical()
    {
        return $this->hasMany(HistoricalClientEquipment::class);
    }


    public function sender()
    {
        if ($this->support) {
            return $this->support;
        }
        if ($this->networkOperator) {
            return $this->networkOperator;
        }
        if ($this->supervisor) {
            return $this->supervisor;
        }
        return $this->client;
    }
}
