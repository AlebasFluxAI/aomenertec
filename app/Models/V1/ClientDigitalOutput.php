<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDigitalOutput extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;


    public const AUTOMATIC = 'automatic';
    public const MANUAL = 'manual';

    public const NC = 'nc';
    public const NO = 'no';


    protected $fillable = [
        'client_id',
        'number',
        'name',
        'status',
        'control_type',
    ];

    public function clientAlertConfiguration()
    {
        return $this->belongsToMany(ClientAlertConfiguration::class, 'client_digital_output_alert_configurations', 'client_alert_configuration_id', 'client_digital_output_id')
            ->withPivot('control_status');
    }

}
