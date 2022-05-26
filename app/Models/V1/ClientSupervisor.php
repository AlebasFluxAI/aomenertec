<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientSupervisor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supervisor_id',
        'client_id',
        'active'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
