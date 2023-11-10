<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTechnician extends Model
{
    use HasFactory;
    use AuditableTrait;
    use PaginatorTrait;


    protected $fillable = [
        'client_id',
        'technician_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
