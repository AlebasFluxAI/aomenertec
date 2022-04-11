<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientTechnician extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'technician_id'
    ];

    public function technicians()
    {
        return $this->belongsToMany(Technician::class, 'client_technicians')->withPivot('active');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
