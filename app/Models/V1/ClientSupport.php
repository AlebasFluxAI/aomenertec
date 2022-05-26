<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'support_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
