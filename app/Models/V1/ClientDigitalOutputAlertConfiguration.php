<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDigitalOutputAlertConfiguration extends Model
{
    use HasFactory;
    use AuditableTrait;
    use PaginatorTrait;


    protected $fillable = [
        'client_alert_configuration_id',
        'client_digital_output_id'
    ];
}
