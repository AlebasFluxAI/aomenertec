<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoltageLevel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;

    protected $fillable = [
        'id',
        'level',
        'description'
    ];
    protected $table = 'voltage_levels';

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
