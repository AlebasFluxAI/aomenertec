<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAlertConfiguration extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;
    use AuditableTrait;

    protected $fillable = [
        'client_id',
        'flag_id',
        'min_alert',
        'max_alert',
        'min_control',
        'max_control',
        'active_control',
        'control_status'

    ];

    public function outputs()
    {
        return $this->belongsToMany(ClientDigitalOutput::class, 'client_digital_output_alert_configurations', 'client_alert_configuration_id', 'client_digital_output_id')
            ->withPivot('control_status');;
    }

    public function clientAlerts()
    {
        return $this->hasMany(ClientAlert::class);
    }

    public function getVariableName()
    {
        $flags_frame = collect(config('data-frame.flags_frame'));
        $variable = $flags_frame->where('id', $this->flag_id)->first();
        return $variable['placeholder'];
    }

    public function clientDigitalOutput()
    {
        return $this->belongsToMany(ClientDigitalOutput::class, 'client_digital_output_alert_configurations', 'client_alert_configuration_id', 'client_digital_output_id')
            ->withPivot('control_status');
    }

}
